<?php

declare(strict_types=1);

namespace App\Service\RegisterConfirmation;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;

/**
 * Register email sending service.
 */
class RegisterConfirmationService
{
    public const EMAIL_SUBJECT = 'Hell Ponies of Miami - Motorcycle Club. Welcome.';

    private MailerInterface $mailer;
    private ParameterBagInterface $parameterBag;

    public function __construct(
        MailerInterface       $mailer,
        ParameterBagInterface $parameterBag
    )
    {
        $this->mailer = $mailer;
        $this->parameterBag = $parameterBag;
    }

    /**
     * Sends an e-mail confirming registration on the service.
     *
     * @param User $user
     * @param string $plainPassword
     *
     * @return true if success.
     * @throws \DomainException If sending fails.
     */
    public function sendMail(
        User                          $user,
        #[\SensitiveParameter] string $plainPassword
    ): true
    {
        try {
            $templatedEmail = new TemplatedEmail();
            $templatedEmail->from($this->parameterBag->get('register_email'))
                ->to((string)$user->getEmail())
                ->subject(self::EMAIL_SUBJECT)
                ->htmlTemplate('service/register_confirmation/register_confirmation_email.html.twig')
                ->context([
                    'user' => $user,
                    'plainPassword' => $plainPassword
                ]);

            $this->mailer->send($templatedEmail);
        } catch (\Throwable $throwable) {
            throw new \DomainException($throwable->getMessage());
        }

        return true;
    }
}
