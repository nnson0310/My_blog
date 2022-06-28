<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use App\Services\Admin\CsvService;

/**
 * @Route("/backdoor/csv", name="csv_")
 */
class CsvController extends AbstractController
{

    protected $csvService;

    public function __construct(CsvService $csvService)
    {
        $this->csvService = $csvService;
    }

    /**
     * @Route("/category/sample/download", name="category_download")
     */
    public function downloadCategoryCsvSample (Request $request): BinaryFileResponse
    {
        //download sample csv for category
        $dir = $request->server->get('DOCUMENT_ROOT').'/assets/files/csv_sample/category_csv_sample.csv';
        $response = new BinaryFileResponse($dir);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'category_csv_sample.csv');
        return $response;
    }

    /**
     * @Route("/topic/sample/download", name="topic_download")
     */
    public function downloadTopicCsvSample (Request $request): BinaryFileResponse
    {
        //download sample csv for topic
        $dir = $request->server->get('DOCUMENT_ROOT').'/assets/files/csv_sample/topic_csv_sample.csv';
        $response = new BinaryFileResponse($dir);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'topic_csv_sample.csv');
        return $response;
    }

    /**
     * @Route("/tags/sample/download", name="tags_download")
     */
    public function downloadTagsCsvSample (Request $request): BinaryFileResponse
    {
        //download sample csv for tags
        $dir = $request->server->get('DOCUMENT_ROOT').'/assets/files/csv_sample/tags_csv_sample.csv';
        $response = new BinaryFileResponse($dir);
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT, 'tags_csv_sample.csv');
        return $response;
    }

    /**
     * @Route("/category/import", name="category_import", methods={"POST"})
     */
    public function importCategoryCsv (Request $request)
    {
        $_token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('category_import', $_token)) {
            $uploadedCsv = $request->files->get('csv');
            $csvFilePointer = fopen($uploadedCsv->getRealPath(), 'r');
            if ($csvFilePointer !== false) {
                while (($lines = fgetcsv($csvFilePointer, 1000, ",")) !== false) {
                    $csvData[] = [
                        'name' => $lines[0],
                        'description' => $lines[1]
                    ];
                }
            }
            foreach ($csvData as $key => $data) {
                //get result from csv file from second row
                if ($key > 0) {
                    $result = $this->csvService->createCategoryFromCsv($data);
                    if (is_string($result)) {
                        $this->addFlash('error', $result);
                        return $this->redirectToRoute('admin_category_index');
                    }
                }
            }
            return $this->redirectToRoute('admin_category_index');
            fclose($csvFilePointer);
        }
        return new Response('Operation not allowed.', Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

    /**
     * @Route("/topic/import", name="topic_import", methods={"POST"})
     */
    public function importTopicCsv (Request $request): Response
    {
        $_token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('topic_import', $_token)) {
            $uploadedCsv = $request->files->get('csv');
            $csvFilePointer = fopen($uploadedCsv->getRealPath(), 'r');
            if ($csvFilePointer !=  false) {
                while (($lines = fgetcsv($csvFilePointer, 1000, ",")) !== false) {
                    $csvData[] = [
                        'category' => $lines[0],
                        'name' => $lines[1],
                        'description' => $lines[2]
                    ];
                }
            }
            foreach ($csvData as $key => $data) {
                if ($key > 0) {
                    $result = $this->csvService->createTopicFromCsv($data);
                }
            }
            if (is_string($result)) {
                $this->addFlash('msg', $result);
                return $this->redirectToRoute('admin_topic_index');
            }
            else if (!$result) {
                $this->addFlash('msg', 'Import topics unsuccessfully. Please try again later.');
                return $this->redirectToRoute('admin_topic_index');
            }
            $this->addFlash('msg', 'Import topics successfully.');
            return $this->redirectToRoute('admin_topic_index');
            fclose($csvFilePointer);
        }
        return new Response('Operation not allowed.', Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

    /**
     * @Route("/tags/import", name="tags_import", methods={"POST"})
     */
    public function importTagsCsv (Request $request)
    {
        $_token = $request->request->get('_token');
        if ($this->isCsrfTokenValid('tags_import', $_token)) {
            $uploadedCsv = $request->files->get('csv');
            $csvFilePointer = fopen($uploadedCsv->getRealPath(), 'r');
            if ($csvFilePointer !== false) {
                while (($lines = fgetcsv($csvFilePointer, 1000, ",")) !== false) {
                    $csvData[] = [
                        'name' => $lines[0],
                    ];
                }
            }
            foreach ($csvData as $key => $data) {
                //get result from csv file from second row
                if ($key > 0) {
                    $result = $this->csvService->createTagsFromCsv($data);
                    if (is_string($result)) {
                        $this->addFlash('msg', $result);
                        return $this->redirectToRoute('admin_tags_index');
                    }
                }
            }
            $this->addFlash('msg', 'Import tags successfully.');
            return $this->redirectToRoute('admin_tags_index');
            fclose($csvFilePointer);
        }
        return new Response('Operation not allowed.', Response::HTTP_BAD_REQUEST, ['Content-type' => 'text/plain']);
    }

}
