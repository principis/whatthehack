<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 02.03.19
 * Time: 10:21
 */

namespace AppBundle\Command;

use AppBundle\Entity\Account;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class AddAccountPhotoCommand extends Command
{
    protected static $defaultName = 'app:add:account-photo';
    private $em;
    private $root;

    public function __construct(EntityManager $em, $root)
    {
        $this->em = $em;
        $this->root = $root;
        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Add account photo')
            ->setName('app:add:account-photo')
            ->addArgument('account', InputArgument::REQUIRED)
            ->addArgument('photo', InputArgument::REQUIRED)
            ->addArgument('firstName', InputArgument::REQUIRED)
            ->addArgument('lastName', InputArgument::REQUIRED)
            ->addArgument('key', InputArgument::REQUIRED);

    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var Account $account
         */
        $account = $this->em->getRepository('AppBundle:Account')->findOneByClient($input->getArgument('account'));
        $username = $input->getArgument('firstName') . '_' . $input->getArgument('lastName');
        $user = null;
        foreach($account->getAccountPhotos() as $accountPhoto) {
            if ($accountPhoto->getUserName() === $username) {
                $user = $accountPhoto;
            }
        }
        $user->setIsRegistered(true);

        exec($this->root . '/../../facial_recognition/venv/bin/python3 ' . $this->root . '/../../facial_recognition/addFace.py "' . $input->getArgument('photo') . '" "' . $input->getArgument('key') .'"', $out);
        $user->setPhotoHash($out[0]);
        $this->em->persist($user);
        $this->em->flush();
    }
}