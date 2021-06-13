<?php

declare(strict_types=1);

// class pour gérer la connection à la base de donnée
namespace App\Service;

// *** exemple fictif d'accès à la base de données
final class Database
{
    private array $bdd;
    private string $table;

    public function __construct()
    {
        /* A retirer - Début - Ne pas analyser ce code*/
        // table user
        $this->bdd['user']['jean@free.fr'] = ['id' => 1, 'email' => 'jean@free.fr', 'pseudo' => 'jean', 'password' => 'password'];
        // table post
        $this->bdd['post'][1] = ['id' => 1, 'title' => 'Article $1 du blog', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas feugiat nec urna ac tristique. Aliquam erat volutpat. Cras augue leo, semper et nibh sed, consectetur euismod diam. Morbi consectetur, est id tempor elementum, turpis ligula blandit nisi, non pulvinar nulla arcu non lectus. Cras bibendum venenatis nulla eget convallis. Suspendisse convallis, dui ac faucibus tempus, massa purus hendrerit tellus, vel congue nisl elit sit amet nunc. Cras tristique porta est vel ullamcorper. Praesent fringilla sapien nisi, eget volutpat felis pretium ac. Donec pretium neque at rutrum rhoncus. Nullam lobortis commodo tellus quis scelerisque. Vestibulum tincidunt diam ac mauris pulvinar vestibulum. Aliquam at nibh non ex egestas molestie in nec massa. Donec varius non metus vel rhoncus. Duis gravida pulvinar tortor, a luctus dui placerat vel.

        Vestibulum rhoncus ligula a pharetra vestibulum. Fusce finibus porta pellentesque. Donec facilisis mi non felis tincidunt convallis. Mauris interdum lacus risus, quis scelerisque erat accumsan eget. Duis lacinia elit ut semper ultrices. Duis tincidunt rhoncus odio, ac congue arcu rhoncus ut. Phasellus nibh nibh, auctor vel ante id, gravida cursus felis. Nullam nulla lectus, maximus in hendrerit id, congue vitae orci. Suspendisse sodales, tellus ut placerat gravida, mi neque egestas erat, in viverra risus est ut metus. Phasellus ante dolor, euismod vitae lectus a, convallis tincidunt eros. Nunc ut posuere nibh. Donec dictum nulla ex, quis dapibus magna iaculis quis. Duis ullamcorper vehicula nunc id mollis. Sed a dui eget metus porta hendrerit.'];
        $this->bdd['post'][25] = ['id' => 25, 'title' => 'Article $25 du blog', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas feugiat nec urna ac tristique. Aliquam erat volutpat. Cras augue leo, semper et nibh sed, consectetur euismod diam. Morbi consectetur, est id tempor elementum, turpis ligula blandit nisi, non pulvinar nulla arcu non lectus. Cras bibendum venenatis nulla eget convallis. Suspendisse convallis, dui ac faucibus tempus, massa purus hendrerit tellus, vel congue nisl elit sit amet nunc. Cras tristique porta est vel ullamcorper. Praesent fringilla sapien nisi, eget volutpat felis pretium ac. Donec pretium neque at rutrum rhoncus. Nullam lobortis commodo tellus quis scelerisque. Vestibulum tincidunt diam ac mauris pulvinar vestibulum. Aliquam at nibh non ex egestas molestie in nec massa. Donec varius non metus vel rhoncus. Duis gravida pulvinar tortor, a luctus dui placerat vel.

        Vestibulum rhoncus ligula a pharetra vestibulum. Fusce finibus porta pellentesque. Donec facilisis mi non felis tincidunt convallis. Mauris interdum lacus risus, quis scelerisque erat accumsan eget. Duis lacinia elit ut semper ultrices. Duis tincidunt rhoncus odio, ac congue arcu rhoncus ut. Phasellus nibh nibh, auctor vel ante id, gravida cursus felis. Nullam nulla lectus, maximus in hendrerit id, congue vitae orci. Suspendisse sodales, tellus ut placerat gravida, mi neque egestas erat, in viverra risus est ut metus. Phasellus ante dolor, euismod vitae lectus a, convallis tincidunt eros. Nunc ut posuere nibh. Donec dictum nulla ex, quis dapibus magna iaculis quis. Duis ullamcorper vehicula nunc id mollis. Sed a dui eget metus porta hendrerit.'];
        $this->bdd['post'][26] = ['id' => 26, 'title' => 'Article $26 du blog', 'text' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas feugiat nec urna ac tristique. Aliquam erat volutpat. Cras augue leo, semper et nibh sed, consectetur euismod diam. Morbi consectetur, est id tempor elementum, turpis ligula blandit nisi, non pulvinar nulla arcu non lectus. Cras bibendum venenatis nulla eget convallis. Suspendisse convallis, dui ac faucibus tempus, massa purus hendrerit tellus, vel congue nisl elit sit amet nunc. Cras tristique porta est vel ullamcorper. Praesent fringilla sapien nisi, eget volutpat felis pretium ac. Donec pretium neque at rutrum rhoncus. Nullam lobortis commodo tellus quis scelerisque. Vestibulum tincidunt diam ac mauris pulvinar vestibulum. Aliquam at nibh non ex egestas molestie in nec massa. Donec varius non metus vel rhoncus. Duis gravida pulvinar tortor, a luctus dui placerat vel.

        Vestibulum rhoncus ligula a pharetra vestibulum. Fusce finibus porta pellentesque. Donec facilisis mi non felis tincidunt convallis. Mauris interdum lacus risus, quis scelerisque erat accumsan eget. Duis lacinia elit ut semper ultrices. Duis tincidunt rhoncus odio, ac congue arcu rhoncus ut. Phasellus nibh nibh, auctor vel ante id, gravida cursus felis. Nullam nulla lectus, maximus in hendrerit id, congue vitae orci. Suspendisse sodales, tellus ut placerat gravida, mi neque egestas erat, in viverra risus est ut metus. Phasellus ante dolor, euismod vitae lectus a, convallis tincidunt eros. Nunc ut posuere nibh. Donec dictum nulla ex, quis dapibus magna iaculis quis. Duis ullamcorper vehicula nunc id mollis. Sed a dui eget metus porta hendrerit.'];
        // table comment
        $this->bdd['comment'][1] = [
            ['id' => 1, 'pseudo' => 'Maurice', 'text' => 'J\'aime bien', 'idPost' => '1'],
            ['id' => 4, 'pseudo' => 'Eric', 'text' => 'bof !!!', 'idPost' => '1'],
        ];
        $this->bdd['comment'][25] = [
            ['id' => 2, 'pseudo' => 'Marc', 'text' => 'Cool', 'idPost' => '25'],
            ['id' => 3, 'pseudo' => 'Jean', 'text' => 'Je n\'ai pas compris', 'idPost' => '25'],
        ];
        $this->bdd['comment'][26] = null;
        /* A retirer - Fin */
    }

    /* A retirer - Début - Ne pas analyser ce code */
    public function prepare(string $sql): void
    {
        $table = explode('from', $sql);
        $table = explode('where', $table[1]);
        $this->table = trim($table[0]);
    }

    public function execute(array $criteria = null): ?array
    {
        if ($criteria !== null) {
            $criteria = array_shift($criteria);

            if (!array_key_exists($criteria, $this->bdd[$this->table])) {
                return null;
            }
            return $this->bdd[$this->table][$criteria];
        }

        return $this->bdd[$this->table];
    }


    /* A retirer - Fin */
}
