<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

#[Route('/api/users')]
class UserController extends AbstractController
{
    private UserService $service;
    private UserRepository $repository;
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(UserService $service, UserRepository $repository, SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->service = $service;
        $this->repository = $repository;
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    #[Route('', methods:['POST'])]
    public function create(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        try {
            $user = $this->service->createUser($data);

            $errors = $this->validator->validate($user);
            if (count($errors) > 0) {
                $errMessages = [];
                foreach ($errors as $error) $errMessages[] = $error->getMessage();
                return new JsonResponse(['errors' => $errMessages], 400);
            }

            $json = $this->serializer->serialize($user, 'json', ['groups' => 'user:read']);
            return new JsonResponse($json, 201, [], true);

        } catch (UniqueConstraintViolationException $e) {
            return new JsonResponse(['error' => 'Email уже используется'], 409);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ошибка сервера: '.$e->getMessage()], 500);
        }
    }

    #[Route('/{id}', methods:['PUT'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $user = $this->repository->find($id);
        if (!$user) return new JsonResponse(['error'=>'User not found'], 404);

        $data = json_decode($request->getContent(), true);

        try {
            $user = $this->service->updateUser($user, $data);

            $errors = $this->validator->validate($user);
            if (count($errors) > 0) {
                $errMessages = [];
                foreach ($errors as $error) $errMessages[] = $error->getMessage();
                return new JsonResponse(['errors' => $errMessages], 400);
            }

            $json = $this->serializer->serialize($user, 'json', ['groups' => 'user:read']);
            return new JsonResponse($json, 200, [], true);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ошибка сервера: '.$e->getMessage()], 500);
        }
    }

    #[Route('/{id}', methods:['DELETE'])]
    public function delete(int $id): JsonResponse
    {
        $user = $this->repository->find($id);
        if (!$user) return new JsonResponse(['error'=>'User not found'], 404);

        try {
            $this->service->deleteUser($user);
            return new JsonResponse(null, 204);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Ошибка сервера: '.$e->getMessage()], 500);
        }
    }

    #[Route('/{id}', methods:['GET'])]
    public function getUserById(int $id): JsonResponse
    {
        $user = $this->repository->find($id);
        if (!$user) return new JsonResponse(['error'=>'User not found'], 404);

        $json = $this->serializer->serialize($user, 'json', ['groups' => 'user:read']);
        return new JsonResponse($json, 200, [], true);
    }
}
