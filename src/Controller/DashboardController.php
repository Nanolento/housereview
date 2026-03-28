<?php

namespace App\Controller;

# Entities
use App\Entity\House;

# Services and models
use App\Service\HouseLoader;

# Other dependencies
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;

class DashboardController extends AbstractController {

    #[Route('/number')]
    public function number(): Response {
        $number = random_int(1, 100);
        return $this->render('number.html.twig', [
            'number' => $number,
        ]);
    }

    #[Route('/')]
    public function main(EntityManagerInterface $em): Response {
        # Get houses
        $houseRepo = $em->getRepository(House::class);
        $houses = $houseRepo->findAll();
        
        return $this->render('dashboard.html.twig', [
            'houses' => $houses,
        ]);
    }

    // This is a test route to make loading houses possible while the ui isnt finished,
    // we will make it so it loads the houses automatically if none were found with the ui.
    #[Route('/load')]
    public function loadHouse(HouseLoader $hl): Response {
        $hl->loadHouses();
        return new Response('<html><body><h1>Houses loaded into database.</h1></body></html>');
    }
    
}

?>
