<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\RegisterConfirmation\RegisterConfirmationService;
use App\Service\RegisterUser\RegisterUserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Uid\Uuid;

class RegisterController extends AbstractController
{
    /**
     * @param Request $request
     * @param RegisterUserService $registerUserService
     * @param RegisterConfirmationService $registerConfirmationService
     *
     * @return JsonResponse|Response Key 'result' for JsonResponse
     */
    #[Route('/', name: 'app_user_register', methods: ['GET', 'POST'])]
    public function register(
        Request                     $request,
        RegisterUserService         $registerUserService,
        RegisterConfirmationService $registerConfirmationService
    ): JsonResponse|Response
    {
        $requestMethod = $request->getMethod();

        if ((Request::METHOD_POST === $requestMethod) && $request->isXmlHttpRequest()) {
            $response = ['result' => ''];

            $plainPassword = (string)Uuid::v4();
            try {
                $data = $request->request->all();
                $data['password'] = $plainPassword;
                $user = $registerUserService->register($data);
                $registerConfirmationService->sendMail($user, $plainPassword);
            } catch (\Throwable $throwable) {
                $response['result'] = 'error: some message';

                //todo kasuj poniższy dump
                return new JsonResponse(['ess' => $throwable->getMessage()], Response::HTTP_CREATED);

                return new JsonResponse($response, Response::HTTP_BAD_REQUEST);
            }
            $response['result'] = $this->generateUrl(
                'app_login',
                [],
                UrlGeneratorInterface::ABSOLUTE_URL
                );

            return new JsonResponse($response, Response::HTTP_CREATED);
        }


        return $this->render('register/register.html.twig');
    }
}
