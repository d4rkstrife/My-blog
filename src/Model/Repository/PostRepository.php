<?php

declare(strict_types=1);

namespace App\Model\Repository;

use App\Model\Entity\Post;
use App\Model\Entity\User;
use App\Service\Database;
use App\Model\Repository\Interfaces\EntityRepositoryInterface;

use \PDO;

final class PostRepository implements EntityRepositoryInterface
{
    private Database $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }

    public function find(int $id): ?Post
    {
        return null;
    }

    public function findOneBy(array $criteria, array $orderBy = null): ?Post
    {
        $stmt = $this->database->getPDO()->prepare('
        SELECT * FROM post
        INNER JOIN user
        ON post.fk_user = user.user_id
        WHERE post.id=:id');
        $stmt->execute($criteria);
        $data = $stmt->fetch();

        if ($data === null) {
            return null;
        }

        $post = new Post();
        $user = new User();
        $user
            ->setId($data['fk_user'])
            ->setPseudo($data['pseudo'])
            ->setName($data['name'])
            ->setSurname($data['surname'])
            ->setEmail($data['mail']);
        $post->setAutor($user)
            ->setId($data['id'])
            ->setTitle($data['title'])
            ->setContent($data['content'])
            ->setChapo($data['chapo'])
            ->setModif($data['date_modif'])
            ->setDate($data['date']);
        return $post;
    }

    public function findBy(array $criteria, array $orderBy = null, int $limit = null, int $offset = null): ?array
    {
        return null;
    }

    public function findAll($page): ?array
    {
        // On détermine le nombre total d'articles
        $sql = 'SELECT COUNT(*) AS nb_articles FROM `post`;';

        // On prépare la requête
        $query = $this->database->getPDO()->prepare($sql);

        // On exécute
        $query->execute();

        // On récupère le nombre d'articles
        $result = $query->fetch();

        $nbArticles = (int) $result['nb_articles'];

        // On détermine le nombre d'articles par page
        $parPage = 4;

        // Calcul du 1er article de la page
        $premier = ($page * $parPage) - $parPage;

        $stmt = $this->database->getPDO()->prepare('
        SELECT * FROM post
        INNER JOIN user    
        ON post.fk_user = user.user_id
        ORDER BY post.date
        DESC LIMIT :premier, :parpage');
        $stmt->bindValue(':premier', $premier, PDO::PARAM_INT);
        $stmt->bindValue(':parpage', $parPage, PDO::PARAM_INT);
        $stmt->execute();
        $data = $stmt->fetchAll();


        if ($data === null) {
            return null;
        }
        $posts = [];
        foreach ($data as $post) {
            $postObj = new Post();
            $user = new User();
            $user
                ->setId($post['fk_user'])
                ->setPseudo($post['pseudo'])
                ->setName($post['name'])
                ->setSurname($post['surname'])
                ->setEmail($post['mail']);
            $postObj
                ->setAutor($user)
                ->setId($post['id'])
                ->setTitle($post['title'])
                ->setContent($post['content'])
                ->setChapo($post['chapo'])
                ->setModif($post['date_modif'])
                ->setDate($post['date']);

            $posts[] = $postObj;
        }
        return $posts;
    }

    public function create(object $post): bool
    {
        return false;
    }

    public function update(object $post): bool
    {
        return false;
    }

    public function delete(object $post): bool
    {
        return false;
    }
}
