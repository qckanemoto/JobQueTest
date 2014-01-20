<?php

namespace Quartet\Bundle\JobQueTestBundle\Controller;

use Quartet\Bundle\JobQueTestBundle\Job\SendMailJob;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function queueAction()
    {
        $job = new SendMailJob();
        $job->queue = 'sendmail';
        $job->args = [
            'from' => 'no-reply@test.com',
            'from_name' => '差出人名',
            'to' => 'target@test.com',
            'subject' => '件名',
            'body' => '本文',
            'is_html' => false,
        ];

        $this->get('bcc_resque.resque')->enqueue($job);

        // Job 管理画面へリダイレクト.
        return $this->redirect($this->generateUrl('BCCResqueBundle_homepage'));
    }
}
