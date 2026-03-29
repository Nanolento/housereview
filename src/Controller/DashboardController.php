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

    #[Route('/')]
    public function main(EntityManagerInterface $em, HouseLoader $hl): Response {
        # Get houses
        $houseRepo = $em->getRepository(House::class);
        $houses = $houseRepo->findAll();

        if (count($houses) === 0) {
            # Load houses into db if there are none in the db.
            if ($hl->loadHouses()) {
                # Get houses again
                $houseRepo = $em->getRepository(House::class);
                $houses = $houseRepo->findAll();
            } else {
                return new Response('<html><body><h1>Could not start dashboard as data could not be loaded.</h1></body></html>');
            }
        }
        
        return $this->render('dashboard.html.twig', [
            'houses' => $houses,
        ]);
    }

    // This is a test route to make loading houses possible while the ui isnt finished,
    // we will make it so it loads the houses automatically if none were found with the ui.
    #[Route('/load')]
    public function loadHouse(HouseLoader $hl): Response {
        if ($hl->loadHouses()) {
            return new Response('<html><body><h1>Houses loaded into database.</h1></body></html>');
        } else {
            return new Response('<html><body><h1>Data could not be loaded. The file does not exist.</h1></body></html>');
        }
    }
    
}

?>
