## Blog Website
Blog project
Change project repository's location from gitlab to github
Gitlab repo: https://gitlab.com/f464/blogger

### Table of Contents
1. [Important note](https://github.com/nnson0310/My_blog#important-note)
2. [Install](https://github.com/nnson0310/My_blog#install)
3. [Screenshots](https://github.com/nnson0310/My_blog#screenshots)
4. [License](https://github.com/nnson0310/My_blog#license)

### Important Note
1. This project is a blog project, main language is PHP and Javascript.
2. Web framework is [`Symfony 5`](https://symfony.com/5) and [`Vuejs 2`](https://vuejs.org/)
3. User site using Vuejs to build UI components, admin site uses twig template of Symfony
4. Backend API mainly using Symfony (Composer to manage dependencies), front-end using yarn
5. Based on `MVC (model-view-route-controller)`
6. Using [doctrineDB](https://www.doctrine-project.org/) to write SQL and interact with DB.

### Install
1. Clone project and run `"composer update"`
2. Run `php bin/console doctrine:migrations:migrate` to migrate database 
2. Run user site: `yarn encore dev-server`
3. Domain for admin site: `your_domain/backdoor/login_form` to go to login page

### Screenshots
![user-site1](https://github.com/nnson0310/My_blog/blob/develop/screenshots/user1.png)

### License
Feel free to clone and use it as you want. If you find this project useful, please give it a star.
