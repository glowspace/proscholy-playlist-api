<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CustomSongLyric;
use App\Models\Playlist;
use App\Models\PlaylistRecord;
use GraphQL\Client;
use GraphQL\Query;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class PlaylistRecordController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Response
     * @throws ValidationException
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'playlist_id'   => 'exists:playlists',
            'type'          => [
                'required',
                'string',
                Rule::in([
                    PlaylistRecord::TYPE_PROSCHOLY,
                    PlaylistRecord::TYPE_CUSTOM,
                ]),
            ],
            'song_lyric_id' => 'required|int',
            'title_tag_id'  => 'int',
            'title_custom'  => 'string',
        ]);

        $playlist = Playlist::findOrFail($request['playlist_id']);

        // Validate custom song exists
        if ($request['type'] == PlaylistRecord::TYPE_CUSTOM)
        {
            $custom = $this->validateCustomSong($request['song_lyric_id']);
            $name   = $custom->name;
        }
        else
        {
            $name = $this->getSongNameForProScholySongLyricsId($request['song_lyric_id']);
        }

        # TODO: authorize

        // Create new playlist record
        $playlist_record                = new PlaylistRecord();
        $playlist_record->playlist_id   = $playlist->id;
        $playlist_record->type          = $request['type'];
        $playlist_record->song_lyric_id = $request['song_lyric_id'];

        $playlist_record = $this->chooseTitleFromRequest($playlist_record, $request);

        $playlist_record->name  = $name;
        $playlist_record->order = $playlist->getNewRecordOrder();
        $playlist_record->save();

        return new Response($playlist_record);
    }


    /**
     * Display the specified resource.
     *
     * @param PlaylistRecord $playlistRecord
     *
     * @return Response
     */
    public function show(PlaylistRecord $playlistRecord): Response
    {
        $this->authorize('view', $playlistRecord->playlist);

        return new Response($playlistRecord);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param Request        $request
     * @param PlaylistRecord $playlistRecord
     *
     * @return Response
     * @throws ValidationException
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function update(Request $request, PlaylistRecord $playlist_record)
    {
        $this->validateUpdateRequest($request);

        $playlist = Playlist::findOrFail($request['playlist_id']);

        $this->authorize('update', $playlist);

        if ($request->has('type'))
        {
            $playlist_record->type = $request['type'];
        }

        if ($request->has('song_lyric_id'))
        {
            $playlist_record->song_lyric_id = $request['song_lyric_id'];
        }

        // Get custom song lyric
        if ($request['type'] == PlaylistRecord::TYPE_CUSTOM)
        {
            $custom = $this->validateCustomSong($request['song_lyric_id']);
            $name   = $custom->name;
        }
        // Fetch ProScholy song lyric
        else
        {
            $name = $this->getSongNameForProScholySongLyricsId($request['song_lyric_id']);
        }

        $playlist_record->name = $name;

        // Title
        $playlist_record = $this->chooseTitleFromRequest($playlist_record, $request);
        $playlist_record->save();

        return new Response($playlist_record);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param PlaylistRecord $playlistRecord
     *
     * @return Response
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function destroy(PlaylistRecord $playlistRecord)
    {
        $this->authorize('delete', $playlistRecord->playlist);



        $playlistRecord->delete();

        return new Response('Playlist record deleted.', Response::HTTP_NO_CONTENT);
    }


    private function validateCustomSong($song_lyric_id)
    {
        return CustomSongLyric::findOrFail($song_lyric_id);
    }


    private function getSongNameForProScholySongLyricsId($song_lyric_id)
    {
        $client = new Client(
            'https://zpevnik.proscholy.cz/graphql'
        );

        $gql = (new Query('song_lyric'))
            ->setArguments(['id' => $song_lyric_id])
            ->setSelectionSet(
                [
                    'name',
                ]
            );


        $res = $client->runQuery($gql);

        return $res->getResults()->data->song_lyric->name;
    }


    private function chooseTitleFromRequest(PlaylistRecord $playlist_record, Request $request): PlaylistRecord
    {
        // Both title tag id and custom string provided.
        if ($request->has('title_tag_id') && $request->has('title_custom'))
        {
            throw ValidationException::withMessages([
                'title_tag_id' => ['You can set only one title_tag_id or one title_custom, but not both at the same time.'],
            ]);
        }

        // Empty title
        if ( ! $request->has('title_tag_id') && ! $request->has('title_custom'))
        {
            $playlist_record->title_tag_id = null;
            $playlist_record->title_custom = null;
        }

        if ($request->has('title_tag_id'))
        {
            $playlist_record->title_tag_id = $request['title_tag_id'];
            $playlist_record->title_custom = null;
        }

        if ($request->has('title_custom'))
        {
            $playlist_record->title_custom = $request['title_custom'];
            $playlist_record->title_tag_id = null;
        }

        return $playlist_record;
    }


    /**
     * @param Request $request
     *
     * @throws ValidationException
     */
    private function validateUpdateRequest(Request $request): void
    {
        $this->validate($request, [
            'type'          => [
                'required',
                'string',
                Rule::in([
                    PlaylistRecord::TYPE_PROSCHOLY,
                    PlaylistRecord::TYPE_CUSTOM,
                ]),
            ],
            'song_lyric_id' => 'required|int',
            'title_tag_id'  => 'int',
            'title_custom'  => 'string',
        ]);
    }
}
