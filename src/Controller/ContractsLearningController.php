<?php

namespace App\Controller;

use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContractsLearningController extends AbstractController
{
    #[Route("/cachetest", name:"cache_index")]
    public function index(): Response
    {
        $cachePool = new FilesystemAdapter();

        // 1. store string values

        // set the cache value using the get() method
        // the first argument is a cach key
        // the second argument is a function that only executed if the cache key is not stored in the cach pool
        // this function is responsable for generating the cache value and returning it
        $value = $cachePool->get('test_string', function (ItemInterface $item) {
            return 'Hello World!';
        });

        // // delete specific item
        // $cachePool->delete('test_string');

        // 2. set expiry on items
        $cachvalue = $cachePool->get('foo', function (ItemInterface $item) {
            $item->expiresAfter(5);
            $cacheItemValue = 'bar';
            return $cacheItemValue;
        });

        return $this->render('cache/contracts.html.twig', [
            'cachedValue' => $value,
            'cacheItemValue' => $cachvalue,
        ]);
    }
}