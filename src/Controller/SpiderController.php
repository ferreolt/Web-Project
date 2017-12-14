<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use App\Entity\Spiders;
use App\Entity\Breeds;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class SpiderController extends Controller
{

  /**
     * @Route("/", name="default")
     */
  public function default()
  {
    /*$repository = $this->getDoctrine()->getRepository(Spiders::class);
        $spiders=$repository->findAll();*/

        return $this->redirectToRoute('spider');
  }

    /**
     * @Route("/spider", name="spider")
     */
    public function index()
    {
     /* $em = $this->getDoctrine()->getManager();

        $breed = new Breeds();
        $breed->setName('Araneidae');
        $breed->setImage('https://upload.wikimedia.org/wikipedia/commons/thumb/4/43/Argiope_catenulata_at_Kadavoor.jpg/220px-Argiope_catenulata_at_Kadavoor.jpg');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($breed);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();*/

      $repository = $this->getDoctrine()->getRepository(Spiders::class);
      $spiders=$repository->findAll();
      $repository = $this->getDoctrine()->getRepository(Breeds::class);
      $breeds=$repository->findAll();
      return $this->render('spider.html.twig', array(
    'spiders' => $spiders,
    'breeds' => $breeds));
    }

    /**
     * @Route("/spider/add", name="spider_add")
     */
    public function addAction(Request $request){
      $spider=new Spiders();
        $form = $this->createFormBuilder($spider)
        ->add('Name',  EntityType::class, array(
    'class' => Breeds::class,
    'choice_label' => 'name',))
            ->add('Price', NumberType::class)
            ->add('Save', SubmitType::class, array('label' => 'Add Spider'))
            ->getForm();

             $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // $form->getData() holds the submitted values
        // but, the original `$task` variable has also been updated
        $spider = $form->getData();
        $em = $this->getDoctrine()->getManager();
         $em->persist($spider);
         $em->flush();
         return $this->redirectToRoute('spider');
       }

            return $this->render('spider/add.html.twig', array(
            'form' => $form->createView(),
        ));
    }

     /**
     * @Route("/spider/remove/{id}", name="spider_remove")
     */
    public function removeAction($id){
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to your action: index(EntityManagerInterface $em)
        $em = $this->getDoctrine()->getManager();

        $spider = $em->getRepository(Spiders::class)->find($id);

        if (!$spider) {
        throw $this->createNotFoundException(
            'No spider found for id '.$id
        );
    }


        $em->remove($spider);
        $em->flush();
      /*  $repository = $this->getDoctrine()->getRepository(Spiders::class);
        $spiders=$repository->findAll();*/

        return $this->redirectToRoute('spider');
    }

    /**
 * @Route("/spider/{id}", name="spider_show")
 */
public function showAction($id)
{
    $spider = $this->getDoctrine()
        ->getRepository(Spiders::class)
        ->find($id);

    $breed = $this->getDoctrine()
        ->getRepository(Breeds::class)
        ->findOneBy(['name' => $spider->getName()]);

    if (!$spider) {
        throw $this->createNotFoundException(
            'No spider found for id '.$id
        );
    }


    // or render a template
    // in the template, print things with {{ spider.name }}
    return $this->render('spider/show.html.twig', array(
    'spider' => $spider,
    'breed' => $breed));
    }


    /**
 * @Route("/spider/update/{id}", name="spider_update")
 */
public function updateAction($id, Request $request)
{
    $spider = $this->getDoctrine()
        ->getRepository(Spiders::class)
        ->find($id);

    if (!$spider) {
        throw $this->createNotFoundException(
            'No spider found for id '.$id
        );
    }

    $form = $this->createFormBuilder($spider)
            ->add('Name',  EntityType::class, array(
    'class' => Breeds::class,
    'choice_label' => 'name',))
            ->add('Price', NumberType::class)
            ->add('Save', SubmitType::class, array('label' => 'Save'))
            ->getForm();

            $form->handleRequest($request);

   if ($form->isSubmitted() && $form->isValid()) {
        // $form->getData() holds the submitted values
        // but, the original `$task` variable has also been updated
        $spider = $form->getData();
        $em = $this->getDoctrine()->getManager();
         $em->persist($spider);
         $em->flush();
         return $this->redirectToRoute('spider');
       }

            return $this->render('spider/update.html.twig', array(
            'form' => $form->createView(),
        ));
}
}
