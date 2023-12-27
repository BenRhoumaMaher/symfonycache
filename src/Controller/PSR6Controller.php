<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Cache\Adapter\FilesystemAdapter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PSR6Controller extends AbstractController
{
    #[Route('/index', name:'index')]
    public function index(): Response
    {
        // initialize cache pool of the file system adapter
        $cachePool = new FilesystemAdapter();

        // 1. store string values

        // use getItem to fetch the cache item with First_Name key
        $Name = $cachePool->getItem('Full_Name');

        // use isHit() to check if the value we're looking for is already present in the cache item $Name
        if (!$Name->isHit()) {
            // set the cache value using the set() method
            $Name->set('Maher Ben Rhouma');
            // save the cache item into the cach pool using save() method
            $cachePool->save($Name);
        }
        // check the existing of the cach item in the cach pool with method hasItem()
        if ($cachePool->hasItem('Full_Name')) {
            // fetch the cache item from the cache
            $Name = $cachePool->getItem('Full_Name');
            $NameValue = $Name->get();
        }

        // delete all items
        // $cachePool->clear();
        // if (!$cachePool->hasItem('Full_Name')) {
        //     $NameValue = "The cache entry Full_Name was deleted successfully!";
        // }

        // 2. store array values
        $Person = $cachePool->getItem('person_array');
        if (!$Person->isHit()) {
            $Person->set(array("one", "two", "three"));
            $cachePool->save($Person);
        }
        if ($cachePool->hasItem('person_array')) {
            $Person = $cachePool->getItem('person_array');
            $personValue = $Person->get();
        }

        // delete specific item
        // $cachePool->deleteItem('person_array');
        // if (!$cachePool->hasItem('person_array')) {
        //     $personValue = "The cache entry person_array was deleted successfully!";
        // }

        // 3. set expiry on items
        $foo = $cachePool->getItem('foo');
        if (!$foo->isHit()) {
            $foo->set('bar');
            $foo->expiresAfter(10);
            $cachePool->save($foo);
        }
        if ($cachePool->hasItem('foo')) {
            $foo = $cachePool->getItem('foo');
            $fooValue = $foo->get();
        }
        sleep(13);
        if ($cachePool->hasItem('foo')) {
            $fooValue = $foo->get();
        } else {
            $fooValue = "cache item has been expired";
        }

        return $this->render('cache/index.html.twig', [
            'nameValue' => $NameValue ?? null,
            'personValue' => $personValue ?? null,
            'foo' => $fooValue ?? null,
        ]);
    }
}