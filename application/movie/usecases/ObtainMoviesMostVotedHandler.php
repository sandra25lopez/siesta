<?php
namespace siesta\application\movie\usecases;

use siesta\domain\movie\infrastructure\MovieProvider;
use siesta\domain\vote\infrastructure\VoteProvider;

class ObtainMoviesMostVotedHandler
{
    /** @var MovieProvider */
    private $_movieProvider;
    /** @var VoteProvider */
    private $_voteProvider;

    /**
     * ObtainMoviesMostVotedHandler constructor.
     * @param MovieProvider $movieProvider
     * @param VoteProvider $voteProvider
     */
    public function __construct(MovieProvider $movieProvider, VoteProvider $voteProvider)
    {
        $this->_movieProvider = $movieProvider;
        $this->_voteProvider = $voteProvider;
    }

    /**
     * @param int $filmFestivalId
     * @return array
     * @throws \siesta\domain\exception\MovieNotFoundException
     */
    public function execute($filmFestivalId): array
    {
        $voteList = $this->_voteProvider->getVotesOfFilmFestivalIdOrderedByScore($filmFestivalId);
        $movieList = [];
        foreach ($voteList as $vote) {
            $movie = $this->_movieProvider->getMovieById($vote->getMovieId());
            $movie->setVote($vote);
            $movieList[] = $movie;
        }

        return $movieList;
    }
}