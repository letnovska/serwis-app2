<?php
/**
 * Post controller.
 */

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\FormType;

/**
 * Class PostController.
 *
 * @Route("/post")
 */
class PostController extends AbstractController
{
    /**
     * Index action.
     *
     * @param \App\Repository\PostRepository            $repository Post repository
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator  Paginator
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/",
     *     methods={"GET"},
     *     name="post_index",
     * )
     */
    public function index(PostRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Post::NUMBER_OF_ITEMS
        );
        $form = $this->createForm(SearchType::class);
        $form->handleRequest($request);

        return $this->render('post/index.html.twig', ['pagination' => $pagination, 'form' => $form->createView()]);
    }

    /**
     * View action.
     *
     * @param \App\Entity\Post                          $post      Post Entity
     * @param \Knp\Component\Pager\PaginatorInterface   $paginator Paginator
     * @param \Symfony\Component\HttpFoundation\Request $request   HTTP request
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @Route(
     *     "/{id}",
     *     name="post_view",
     *     requirements={"id": "[1-9]\d*"},
     * )
     */
    public function view(Post $post,
                         PostRepository $repository,
                         PaginatorInterface $paginator,
                         Request $request,
                         $id): Response
    {
        $pagination = $paginator->paginate(
            $repository->queryAll(),
            $request->query->getInt('page', 1),
            Post::NUMBER_OF_ITEMS
        );

        return $this->render(
            'post/view.html.twig',
            [
                'post' => $post,
                'pagination' => $pagination,
            ]
        );
    }

    /**
     * New action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Repository\PostRepository            $repository Post repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/new",
     *     methods={"GET", "POST"},
     *     name="post_new",
     * )
     */
    public function new(Request $request, PostRepository $repository): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setCreatedAt(new \DateTime());
            $repository->save($post);
            /*
             * Potwierdzienie zapisania
             */
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/new.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Post                          $post       Post entity
     * @param \App\Repository\PostRepository            $repository Post repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/edit",
     *     methods={"GET", "PUT"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="post_edit",
     * )
     */
    public function edit(Request $request, Post $post, PostRepository $repository): Response
    {
        $form = $this->createForm(PostType::class, $post, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUpdatedAt(new \DateTime());
            $repository->save($post);

            $this->addFlash('success', 'message.updated_successfully');

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/edit.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }
    /**
     * Delete action.
     *
     * @param \Symfony\Component\HttpFoundation\Request $request    HTTP request
     * @param \App\Entity\Post                         $post      Lessons entity
     * @param \App\Repository\PostRepository           $repository Lessons repository
     *
     * @return \Symfony\Component\HttpFoundation\Response HTTP response
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     *
     * @Route(
     *     "/{id}/delete",
     *     methods={"GET", "DELETE"},
     *     requirements={"id": "[1-9]\d*"},
     *     name="post_delete",
     * )
     */
    public function delete(Request $request, Post $post, PostRepository $repository): Response
    {
        $form = $this->createForm(FormType::class, $post, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $repository->delete($post);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('post_index');
        }

        return $this->render(
            'post/delete.html.twig',
            [
                'form' => $form->createView(),
                'post' => $post,
            ]
        );
    }
}
