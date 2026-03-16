<?php
namespace App\Application\Controller;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class PiloteController
{
    public function inscription(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Pilotes/Page_Inscription_Pilote.html.twig', []);
    }

    public function liste(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
                $view = Twig::fromRequest($request);

    return $view->render($response, 'Pilotes/Page_Liste_Pilote.html.twig', [
'c1' =>[
'Id'=> '1',
'Prénom' =>'Mark',
'Nom' => 'Otto',
'Email' => '@mdo',
'Campus' => 'Cesi Ecole d"ingénieurs',
'Localisation' => 'Paris',
],
'c2' =>[
'Id'=> '2',
'Prénom' =>'Jacob',
'Nom' => 'Thornton',
'Email' => '@fat',
'Campus' => 'Cesi Ecole d"ingénieurs',
'Localisation' => 'Lyon',
],
'c3' => [
'Id'=> '3',
'Prénom' =>'John',
'Nom' => 'Doe',
'Email' => '@social',
'Campus' => 'Cesi Ecole d"ingénieurs',
'Localisation' => 'Marseille',
],
        ]);
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Pilotes/Page_Liste_Pilote.html.twig', [['mes_étudiants' => $liste_étudiants]]);
    }
    

    public function supprimer(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Pilotes/Page_Supprimer_Pilote.html.twig', []);
    }

    public function modifier(ServerRequestInterface $request, ResponseInterface $response, array $args): ResponseInterface
    {
        $view = Twig::fromRequest($request);
        return $view->render($response, 'Pilotes/Page_Modifier_Pilote.html.twig', []);
    }

    public function traiterInscription(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $donneesFormulaire = $request->getParsedBody();

        $nom      = $donneesFormulaire['nom_pilote'] ?? '';
        $tel      = $donneesFormulaire['telephone'] ?? '';
        $email    = $donneesFormulaire['email'] ?? '';

        try {
            error_log("Pilote enregistré : " . $nom);
        } catch (\Exception $e) {
            error_log("Erreur : " . $e->getMessage());
        }

}

}



