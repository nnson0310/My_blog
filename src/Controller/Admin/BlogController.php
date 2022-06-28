<?php

namespace App\Controller\Admin;

use App\Entity\Blog;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Form\Type\BlogType;
use App\Services\Admin\AdminService;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Admin\BlogService;
use App\Services\Admin\UploadService;
use Symfony\Component\Filesystem\Filesystem;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Services\Admin\MailerService;
use App\Services\Admin\SubscriberService;

/**
 * @Route("/backdoor/blog", name="admin_blog_")
 */
class BlogController extends AbstractController
{
    protected $blogService;

    protected $adminService;

    protected $paginator;

    protected $requestStack;

    protected $uploadService;

    protected $filesystem;

    protected $mailerService;

    protected $subscriberService;

    public function __construct(
        BlogService $blogService,
        PaginatorInterface $paginator,
        RequestStack $requestStack,
        AdminService $adminService,
        UploadService $uploadService,
        Filesystem $filesystem,
        MailerService $mailerService,
        SubscriberService $subscriberService
    ) {
        $this->blogService = $blogService;
        $this->adminService = $adminService;
        $this->paginator = $paginator;
        $this->requestStack = $requestStack;
        $this->uploadService = $uploadService;
        $this->filesystem = $filesystem;
        $this->mailerService = $mailerService;
        $this->subscriberService = $subscriberService;
    }

    /**
     * @Route("/index", name="index", methods={"GET"})
     */
    public function index(Request $request): Response
    {
        $queryBuilder = $this->blogService->getAllBlogsByQueryBuilder();
        $blogs = $this->paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );
        return $this->render('admin/dashboard/blog/index.html.twig', [
            'blogs' => $blogs,
        ]);
    }

    /**
     * @Route("/search", name="search", methods={"POST", "GET"})
     */
    public function search(Request $request)
    {
        $keyword = $request->query->get("keyword");
        $query = $this->blogService->searchBlog($keyword);
        $blogs = $this->paginator->paginate(
            $query,
            $request->query->getInt("page", 1),
            $request->query->getInt("limit", 5)
        );
        return $this->render('admin/dashboard/blog/index.html.twig', [
            'blogs' => $blogs
        ]);
    }

    /**
     * @Route("/edit/form/{id}", name="edit_form", methods={"GET", "POST"})
     * @param Request $request
     * @param Blog $blog
     */
    public function edit(Request $request, Blog $blog, $id)
    {
        try {
            $this->blogService->setThumbnailByBlogId($id);

            $form = $this->createForm(BlogType::class, $blog);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $data = $form->getData();
                $thumbnail = $form->get('thumbnail')->getData();
                $blog = $this->blogService->getBlogById($id);
                $pathToOldFile = $blog->getThumbnail();
                /* get username of admin who create blog */
                $session = $this->requestStack->getSession();
                $admin = $session->get('username');
                $lastModifiedBy = $this->adminService->getAdminByUsername($admin);
                $getFileName = explode($this->getParameter('blog_thumbnail_directory_name'), $blog->getThumbnail());
                $fileName = $getFileName[1];

                /* Delete old thumbnail and update new thumbnail */
                if ($thumbnail && $this->filesystem->exists($pathToOldFile)) {
                    $this->filesystem->remove($pathToOldFile);
                    $fileName = $this->uploadService->uploadFile($thumbnail);
                }

                $result = $this->blogService->updateBlog($id, $data, $lastModifiedBy, $fileName);
                if ($result) {
                    $this->addFlash('msg', 'Update blog successfully.');
                    return $this->redirectToRoute('admin_blog_index');
                }
                $this->addFlash('msg', 'Unable to update blog due to some server error. Please try again later.');
                return $this->redirectToRoute('admin_blog_index');
            }

            return $this->renderForm('admin/dashboard/blog/edit_form.html.twig', [
                'form' => $form,
            ]);
        } catch (\Exception $e) {
            $this->addFlash('msg', 'Unable to update blog due to some server error. Please try again later.');
            return $this->redirectToRoute('admin_blog_index');
        }
    }

    /**
     * @Route("/create/form", name="create_form", methods={"GET", "POST"})
     */
    public function createBlogForm(Request $request)
    {
        $form = $this->createForm(BlogType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            /* Upload thumbnail */
            $thumbnail = $form->get('thumbnail')->getData();
            if ($thumbnail) {
                $fileName = $this->uploadService->uploadFile($thumbnail);
                /* get username of admin who create blog */
                $session = $this->requestStack->getSession();
                $admin = $session->get('username');
                $createdBy = $this->adminService->getAdminByUsername($admin);
                $result = $this->blogService->createNewBlog($data, $createdBy, $fileName);

                if ($result) {
                    $this->addFlash('msg', 'Create a new blog successfully.');
                    return $this->redirectToRoute('admin_blog_index');
                }
                $this->addFlash('msg', 'Unable to create new blog due to some server error. Please try again.');
                return $this->redirectToRoute('admin_blog_create_form');
            }
            $this->addFlash('error', 'Please choose an image.');
            return $this->redirectToRoute('admin_blog_create_form');
        }
        return $this->renderForm('admin/dashboard/blog/create_form.html.twig', [
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete", name="delete", methods={"POST"})
     * @param Request $request
     */
    public function deleteBlog(Request $request)
    {
        $blogId = $request->request->get('blog_id');

        $result = $this->blogService->deleteBlog($blogId);

        if ($result) {
            $this->addFlash('msg', 'Delete blog successfully.');
            return $this->redirectToRoute('admin_blog_index');
        }
        $this->addFlash('msg', 'Unable to delete blog due to some server error. Please try again later.');
        return $this->redirectToRoute('admin_blog_index');
    }

    /**
     * @Route("/notification/email/send", name="notification_email", methods={"POST"})
     */
    public function sendNotificationEmail(Request $request)
    {
        $_token = $request->request->get("_token");

        if ($this->isCsrfTokenValid("send_notification_email", $_token)) {
            $blogId = $request->request->get("blog_id");
            $blog = $this->blogService->getBlogById($blogId);
            $subscriberEmails = $this->subscriberService->getAllSubscriberEmails();
            if ($blog && count($subscriberEmails) > 0) {
                /* dd($subscriberEmails); */
                $this->mailerService->sendNotificationEmail($subscriberEmails, $blog);
                $this->blogService->changeNotificationEmail($blogId);
                $this->addFlash("msg", "Sending notification email for this post successfully.");
                return $this->redirectToRoute("admin_blog_index");
            }
            $this->addFlash("msg", "Can not send notification email because of server error. Please try again later.");
            return $this->redirectToRoute("admin_blog_index");
        }
        return new Response("Operation not allowed", Response::HTTP_BAD_REQUEST, ["Content-type" => "text/plain"]);
    }

    /**
     * @Route("/recycle", name="recycle", methods={"GET"})
     */
    public function recycle(Request $request)
    {
        $query = $this->blogService->getDeletedBlogs();
        $blogs = $this->paginator->paginate($query, $request->request->getInt('page', 1), 5);
        return $this->render('admin/dashboard/blog/recycle.html.twig', [
            'blogs' => $blogs
        ]);
    }

    /**
     * @Route("/restore", name="restore", methods={"POST"})
     */
    public function restoreBlog(Request $request)
    {
        $_token = $request->request->get("_token");

        if ($this->isCsrfTokenValid("restore_blog", $_token)) {
            $blogId = $request->request->get("blog_id");
            $result = $this->blogService->restoreDeletedBlog($blogId);
            if ($result) {
                $this->addFlash('msg', 'Restore blog successfully.');
                return $this->redirectToRoute('admin_blog_recycle');
            }
            $this->addFlash('msg', 'Restore blog unsuccessfully. Please try again later.');
            return $this->redirectToRoute('admin_blog_recycle');
        }

        return new Response("Operation not allowed", Response::HTTP_BAD_REQUEST, ["Content-type" => "text/plain"]);
    }
}
