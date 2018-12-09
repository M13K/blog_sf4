<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\ArticleType;
use App\Form\CommentType;
use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BlogController
 * @package App\Controller
 */
class BlogController extends AbstractController
{


    // Au lieu de faire de demander à doctrine le repo qui nous interesse à savoir par ex: $repo = $this->getDoctrine()->getRepository(Article::class);
    //On peut directement lui passer le repo concerné à savoir ArticleRepository, grâce à l' INJECTION DE DÉPENDANCES.

    /**
     * @param ArticleRepository $repo
     * @param CategoryRepository $catRepo
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @Route("/blog", name="blog")
     */

    public function index(ArticleRepository $repo, CategoryRepository $catRepo)
    {
        $articles = $repo->findAll();
        $categories = $catRepo->findAll();

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles,
            'categories' => $categories
        ]);
    }


    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/", name="home")
     */
    public function home(){
        return $this->render('blog/home.html.twig');
    }
//
//    /**
//     * @param ArticleRepository $repo
//     * @param Category $category
//     * @return \Symfony\Component\HttpFoundation\Response
//     * @Route("/blog/categories/{category}", name="blog_category")
//     */
//
//    public function findByCategorie(ArticleRepository $repo, Category $category){
//        $articles = $repo->getArticleAvecCategorie($category);
//
//        return $this->render('blog/category.html.twig', [
//            'articles' => $articles
//        ]);
//    }

//    /**
//     * @param ArticleRepository $repo
//     * @param Category $category
//     * @param CategoryRepository $catRepo
//     * @return \Symfony\Component\HttpFoundation\Response
//     * @Route("/blog/articles/categories/{id}", name="blog_categories")
//     */
//    public function findByCategory(ArticleRepository $repo, Category $category, CategoryRepository $catRepo){
//        $articles = $repo->getArticleByCategorie($category);
//        $categories = $catRepo->findAll();
//
//        return $this->render('blog/category.html.twig', [
//            'articles' => $articles,
//            'id' => $category->getId(),
//            'category' => $category->getTitle(),
//            'categories' => $categories
//        ]);
//    }

//    /**
//     * @param ArticleRepository $repo
//     * @param int $categoryId
//     * @return \Symfony\Component\HttpFoundation\Response
//     * @Route("/blog/articles/{categoryId}", name="blog_categories",  requirements={"categoryId"="\d+"})
//     */
//    public function getArtciles(ArticleRepository $repo, int $categoryId){
//        $articles = $repo->getArticleByCategorie($categoryId);
//
//        return $this->render('blog/category.html.twig', [
//            'articles' => $articles
//        ]);
//    }

    /**
     * @param ArticleRepository $repo
     * @param string $title
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("blog/articles/categories/{title}", name="blog_categories")
     */

    public function getArticleByCategory(ArticleRepository $repo, string $title)
    {
        $articles = $repo->findByCategory($title);

        return $this->render('blog/category.html.twig', [
            'articles'=>$articles,
            'title'=>$title

        ]);
    }

    /**
     * @param Article|null $article
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/blog/new", name="blog_create")
     * @Route("/blog/{id}/edit", name="blog_edit")
     */

    public function form(Article $article = null, Request $request, ObjectManager $manager){

        if (!$article){
            $article = new Article();
        }


//        $form = $this->createFormBuilder($article)
//                     ->add('title')
//                     ->add('content')
//                     ->add('image')
//
//                     ->getForm();

        //Dans le cas où l'on crée le form via la classe ArticleType(méthode choisie)

        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            if (!$article->getId()){
                $article->setCreatedAt(new \DateTime());
            }


            $manager->persist($article);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' => $article->getId()]);
        }

        return $this->render('blog/create.html.twig', [

                'formArticle' => $form->createView(),
                'editMode' => $article->getId() !== null
        ]);
    }


//    public function show(ArticleRepository $repo, $id){
//
//        $article = $repo->find($id);
//        return $this->render('blog/show.html.twig',[ 'article' => $article]);
//    }

// La 2e solution que je vais adopter est de passer directement un objet article à la fonction et grâce au param converter symfony sait qu'il doit aller chercher un article avec pour id $id


    /**
     * @param Article $article
     * @param Request $request
     * @param ObjectManager $manager
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     *
     * @Route("/blog/{id}", name="blog_show")
     */

    public function show(Article $article, Request $request, ObjectManager $manager){

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article);
            $manager->persist($comment);
            $manager->flush();

            return $this->redirectToRoute('blog_show', ['id' =>$article->getId()]);
        }

        return $this->render('blog/show.html.twig',[ 'article' => $article,
               'formComment' => $form->createView()
        ]);
    }


//
//    public function showArticleByCategory(ArticleRepository $repo,  Article $article){
//        $category = $article->getCategory();
//        $articlesByCategory = $repo->findByCategorie($category);
//
//        return $this->render('blog/category.html.twig', ['articles' =>$articlesByCategory]);
//    }



}
