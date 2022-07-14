<?php

namespace Database\Seeders;

use App\Repository\GroupRepository;
use App\Repository\PlaylistRepository;
use App\Repository\UserRepository;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $user_repository     = new UserRepository();
        $playlist_repository = new PlaylistRepository();
        $group_repository = new GroupRepository();

        $user_1 = $user_repository->createUser();
        $user_2 = $user_repository->createUser();

        $playlist_1 = $playlist_repository->createUserPlaylist('Janův playlist 1', $user_1, true);
        $playlist_2 = $playlist_repository->createUserPlaylist('Janův playlist 2', $user_1, true);
        $playlist_3 = $playlist_repository->createUserPlaylist('Petrův playlist 1', $user_2, true);
        $playlist_4 = $playlist_repository->createUserPlaylist('Petrův playlist 2', $user_2, true);

        $playlist_1->addSongLyricRecord(1);
        $playlist_1->addSongLyricRecord(2);
        $playlist_1->addSongLyricRecord(3);

        $playlist_2->addSongLyricRecord(10);
        $playlist_2->addSongLyricRecord(11);
        $playlist_2->addSongLyricRecord(12);

        $playlist_3->addSongLyricRecord(1);
        $playlist_3->addSongLyricRecord(2);
        $playlist_3->addSongLyricRecord(3);

        $playlist_4->addSongLyricRecord(10);
        $playlist_4->addSongLyricRecord(11);
        $playlist_4->addSongLyricRecord(12);
    }
}
