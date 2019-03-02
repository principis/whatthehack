<?php
/**
 * Created by PhpStorm.
 * User: arthur
 * Date: 02.03.19
 * Time: 11:10
 */

namespace AppBundle\Command;

use AppBundle\Entity\Account;
use AppBundle\Entity\Transaction;
use Doctrine\ORM\EntityManager;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExecuteTransactionCommand extends Command
{
    protected static $defaultName = 'app:execute:transaction';
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
            ->setDescription('Execute transaction')
            ->setName('app:execute:transaction')
            ->addArgument('account', InputArgument::REQUIRED)
            ->addArgument('photo', InputArgument::REQUIRED)
            ->addArgument('amount', InputArgument::REQUIRED)
            ->addArgument('description', InputArgument::REQUIRED)
            ->addArgument('location', InputArgument::REQUIRED)
            ->addArgument('key', InputArgument::REQUIRED);
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        /**
         * @var Account $account
         */
        $account = $this->em->getRepository('AppBundle:Account')->findOneByClient($input->getArgument('account'));

        foreach($account->getAccountPhotos() as $accountPhoto) {
            if ($accountPhoto->isRegistered() && !$accountPhoto->isDisabled()) {
                $out = exec($this->root . '/../../facial_recognition/venv/bin/python3 ' .
                               $this->root . '/../../facial_recognition/recognizer.py "' .
                               $input->getArgument('photo') . '" "' .
                               $input->getArgument('key') . '" "' . $accountPhoto->getPhotoHash() . '"', $out);

                if ($out === "0") {
                    $trans = new Transaction();
                    $trans->setAccount($account);
                    $trans->setAmount($input->getArgument('amount'));
                    $trans->setLocation($input->getArgument('location'));
                    $trans->setDescription($input->getArgument('description'));
                    $trans->setTransactionDate(new \DateTime());
                    $trans->setAccountPhoto($accountPhoto);

                    $this->em->persist($trans);
                    $this->em->flush();
                    return;
                }
            }
        }
        $trans = new Transaction();
        $trans->setAccount($account);
        $trans->setAmount($input->getArgument('amount'));
        $trans->setLocation($input->getArgument('location'));
        $trans->setDescription($input->getArgument('description'));
        $trans->setTransactionDate(new \DateTime());
        $trans->setAccountPhoto(null);

        $trans->setRefused(true);
        $this->em->persist($trans);
        $this->em->flush();

        $output->writeln('[ERROR] Access Denied!');

    }
}