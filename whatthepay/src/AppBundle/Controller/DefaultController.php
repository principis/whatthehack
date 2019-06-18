<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Account;
use AppBundle\Entity\AccountPhoto;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManager;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Form\AccountPhotoType;
use AppBundle\Service\EnclaveService;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     * @param Request $request
     * @param EnclaveService $enclave
     * @return JsonResponse|RedirectResponse|Response
     */
    public function indexAction(Request $request, EnclaveService $enclave, ObjectManager $em)
    {

        if ($request->request->has('photo_toggle')) {
            $photo = $this->getDoctrine()->getRepository('AppBundle:AccountPhoto')->find($request->request->get('photo_toggle')['id']);
            $photo->setDisabled(filter_var($request->request->get('photo_toggle')['value'], FILTER_VALIDATE_BOOLEAN));
            $em->persist($photo);
            $em->flush();
            return $this->json(array('isValid' => true, 'data' => $photo->isDisabled() ? 'Face recognition disabled!' : 'Face recognition enabled!'));
        }

        $form = $this->createFormBuilder()
            ->add('image', FileType::class, ['label' => 'image'])
            ->add('token', TextType::class, ['label' => 'Secure Key'])
            ->add('submit', SubmitType::class)
            ->getForm();
        if ($request->request->has('photo_register')) {
            $photo = $this->getDoctrine()->getRepository('AppBundle:AccountPhoto')->find($request->request->get('photo_register'));
            return $this->json(array('isValid' => true, 'view' => $this->renderView('form/register.html.twig', array('form' => $form->createView(), 'photo' => $photo))));
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var AccountPhoto $accountPhoto */
            $accountPhoto = $this->getDoctrine()->getRepository(AccountPhoto::class)->find($request->request->get('id'));

            /** @var UploadedFile $file */
            $file = $form->getData()['image'];

            $key = $enclave->GenerateKey($form->getData()['token'], $accountPhoto->getAccount()->getPassword());

            try {
                $hash = $enclave->GeneratePhotoHash($file->getPathname(), $key);
            } catch (InvalidArgumentException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirect($this->generateUrl('homepage'));
            }

            $accountPhoto->setPhotoHash($hash);
            $accountPhoto->setIsRegistered(true);
            $em->persist($accountPhoto);
            $em->flush();
            $this->addFlash('error', 'User successfully registered!');
        }

        /** @var Account $account */
        $account = $this->getDoctrine()
            ->getRepository('AppBundle:Account')
            ->findOneByClient($this->getUser()->getUserName());

        if ($request->request->has('photo_delete')) {
            $photo = $this->getDoctrine()->getRepository('AppBundle:AccountPhoto')
                ->find($request->request->get('photo_delete'));

            $em->remove($photo);
            $em->flush();
            return $this->json(array('isValid' => true));
        }

        $em = $this->getDoctrine()->getManager();

        $newPhoto = new AccountPhoto();
        $newPhoto->setAccount($account);
        $addPhotoForm = $this->createForm(AccountPhotoType::class, $newPhoto);

        $addPhotoForm->handleRequest($request);
        if ($addPhotoForm->isSubmitted() && $addPhotoForm->isValid()) {
            $em->persist($newPhoto);
            $em->flush();

            return $this->redirect($this->generateUrl('homepage'));
        }

        return $this->render('default/index.html.twig', [
            'account' => $account,
            'photo_form' => $addPhotoForm->createView()
        ]);
    }

    /**
     * @Route("/edit", name="edit_photo")
     * @param Request $request
     */
    public function editPhotoAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if ($request->request->has('photo_edit')) {
            $photo = $this->getDoctrine()
                ->getRepository('AppBundle:AccountPhoto')
                ->find($request->request->get('photo_edit'));
        } else {
            $photo = new AccountPhoto();
        }
        $editForm = $this->createForm(AccountPhotoType::class, $photo, [
            'action' => $this->generateUrl('edit_photo'),
        ]);
        $editForm->handleRequest($request);

        if (!$editForm->isSubmitted()) {

            $editForm->setData($photo);
            return $this->json(
                array(
                    'isValid' => true,
                    'view' => $this->renderView('form/form.html.twig', array('photo_form' => $editForm->createView(), 'photo' => $photo))
                )
            );
        }
        $em->persist($photo);
        $em->flush();

        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/register", name="register_photo")
     * @param Request $request
     * @param EnclaveService $enclave
     * @param ObjectManager $em
     * @return JsonResponse|RedirectResponse
     */
    public function registerPhotoAction(Request $request, EnclaveService $enclave, ObjectManager $em) {
        $form = $this->createFormBuilder(null, ['action' => $this->generateUrl('register_photo')])
            ->add('image', FileType::class, ['label' => 'image'])
            ->add('token', TextType::class, ['label' => 'Secure Key'])
            ->add('submit', SubmitType::class)
            ->getForm();
        if ($request->request->has('photo_register')) {
            $photo = $this->getDoctrine()->getRepository('AppBundle:AccountPhoto')->find($request->request->get('photo_register'));
            return $this->json(array('isValid' => true, 'view' => $this->renderView('form/register.html.twig', array('form' => $form->createView(), 'photo' => $photo))));
        }

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            /** @var AccountPhoto $accountPhoto */
            $accountPhoto = $this->getDoctrine()->getRepository(AccountPhoto::class)->find($request->request->get('id'));
            /** @var UploadedFile $file */
            $file = $form->getData()['image'];

            $key = $enclave->GenerateKey($form->getData()['token'], $accountPhoto->getAccount()->getPassword());

            try {
                $hash = $enclave->GeneratePhotoHash($file->getPathname(), $key);
            } catch (InvalidArgumentException $e) {
                $this->addFlash('error', $e->getMessage());
                return $this->redirect($this->generateUrl('homepage'));
            }

            $accountPhoto->setPhotoHash($hash);
            $accountPhoto->setIsRegistered(true);
            $em->persist($accountPhoto);
            $em->flush();
            $this->addFlash('error', 'User successfully registered!');
        }

        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/validate", name="validate_photo")
     * @param Request $request
     * @param EnclaveService $enclave
     * @param ObjectManager $em
     * @return JsonResponse
     */
    public function validatePhotoAction(Request $request, EnclaveService $enclave, ObjectManager $em) {

        $form = $this->createFormBuilder(null, ['action' => $this->generateUrl('validate_photo')])
            ->add('image', FileType::class, ['label' => 'image'])
            ->add('token', TextType::class, ['label' => 'Secure Key'])
            ->add('submit', SubmitType::class)
            ->getForm();

        if ($request->request->has('photo_validate')) {
            $photo = $this->getDoctrine()->getRepository('AppBundle:AccountPhoto')->find($request->request->get('photo_validate'));
            return $this->json(array('isValid' => true, 'view' => $this->renderView('form/register.html.twig', array('form' => $form->createView(), 'photo' => $photo))));
        }

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            /** @var AccountPhoto $accountPhoto */
            $accountPhoto = $this->getDoctrine()->getRepository(AccountPhoto::class)->find($request->request->get('id'));
            /** @var UploadedFile $file */
            $file = $form->getData()['image'];
            $key = $enclave->GenerateKey($form->getData()['token'], $accountPhoto->getAccount()->getPassword());
            dump($key);
            dump($file);
            return $this->json(['isValid' => $enclave->CheckMatch($accountPhoto->getPhotoHash(), $file->getPathname(), $key)]);
        }
        return $this->json(['isValid' => 'error']);
    }
}
