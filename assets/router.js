import AboutMe from './components/pages/AboutMe';
import Contact from './components/pages/Contact';
import Homepage from './components/pages/Homepage';
import PostDetails from './components/pages/PostDetails';
import PageNotFound from './components/pages/PageNotFound';
import LoadMorePosts from './components/pages/LoadMorePosts';
import CategoryPosts from './components/pages/CategoryPosts';
import TagPosts from './components/pages/TagPosts';
import SearchResult from './components/search/SearchResult';

const router  = [
    {
        path: '/', 
        redirect: '/home'
    },
    {
        name: 'Homepage',
        path: '/home', 
        component: Homepage
    },
    {
       name: 'AboutMe',
       path: '/about_me', 
       component: AboutMe
    },
    {
        name: 'Contact',
        path: '/contact', 
        component: Contact
    },
    {
        name: 'LoadMorePosts',
        path: '/posts/load_more',
        component: LoadMorePosts,
        params: true
    },
    {
        name: 'CategoryPosts',
        path: '/category/:slug',
        component: CategoryPosts
    },
    {
        name: 'TagPosts',
        path: '/tag/:slug',
        component: TagPosts
    },
    {
        name: 'SearchResult',
        path: '/search',
        component: SearchResult,
        params: true
    },
    {
        name: 'PostDetails',
        path: '/:slug',
        component: PostDetails,
        params: true
    },
    {
        name: 'PageNotFound',
        path: '*',
        component: PageNotFound
    },
];

export default router;
