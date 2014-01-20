<?php
/**
 * Created by PhpStorm.
 * User: t-kanemoto
 * Date: 2014/01/20
 * Time: 14:09
 */

namespace Quartet\Bundle\JobQueTestBundle\Job;

use BCC\ResqueBundle\ContainerAwareJob as BaseJob;

class SendMailJob extends BaseJob
{
    public function run($args)
    {
        $container = $this->getContainer();

        $message = \Swift_Message::newInstance()
            ->setFrom($args['from'], $args['from_name'])
            ->setTo($args['to'])
            ->setSubject($args['subject'])
            ->setBody($args['body'], $args['is_html'] ? 'text/html' : 'text/plain')
        ;

        $container->get('mailer')->send($message);

        // Job からだと spool に溜まったメールが自動で送られないので, 手動で送信.
        $container->get('swiftmailer.spool')->flushQueue($container->get('swiftmailer.mailer.default.transport.real'));
    }
}
