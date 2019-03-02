<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Entity\AccountPhoto;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function indexAction(Request $request)
    {
        // TODO add validation
        if ($request->request->has('photo_edit')) {
            $photo = $this->getDoctrine()->getRepository('AppBundle:AccountPhoto')->find($request->request->get('photo_edit'));
            $editForm = $this->createForm('AppBundle\Form\AccountPhotoType', $photo);
            return $this->json(array('isValid' => true, 'view' => $this->renderView('form/form.html.twig', array('photo_form' => $editForm->createView(), 'photo' => $photo))));
        }
        /** @var Account $account */
        $account = $this->getDoctrine()
            ->getRepository('AppBundle:Account')
            ->findOneByClient($this->getUser());

        if ($request->request->has('photo_delete')) {
            $photo = $this->getDoctrine()->getRepository('AppBundle:AccountPhoto')
                ->find($request->request->get('photo_delete'));

            $em = $this->getDoctrine()->getManager();
            $em->remove($photo);
            $em->flush();
            return $this->json(array('isValid' => true));
        }

        $em = $this->getDoctrine()->getManager();

        $newPhoto = new AccountPhoto();
        $newPhoto->setAccount($account);
        $addPhotoForm = $this->createForm('AppBundle\Form\AccountPhotoType', $newPhoto);

        $addPhotoForm->handleRequest($request);
        if ($addPhotoForm->isSubmitted() && $addPhotoForm->isValid()) {
            $em->persist($newPhoto);
            $em->flush();

            return $this->redirect($this->generateUrl('homepage'));
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'account' => $account,
            'photo_form' => $addPhotoForm->createView()
        ]);
    }


}
