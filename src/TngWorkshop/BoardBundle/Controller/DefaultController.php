<?php

namespace TngWorkshop\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use TngWorkshop\BoardBundle\Service\BoardService;
use TngWorkshop\BoardBundle\Util\HashTagFinder;

class DefaultController extends Controller
{
    const BOARD_SERVICE = 'board.board_service';
    const DEFAULT_ENTRIES_PER_PAGE = 10;

    public function indexAction(Request $request)
    {
        /** @var BoardService $boardService */
        $boardService = $this->get(self::BOARD_SERVICE);

        $message = "";
        if ($this->hasPostDataForNewMessage($request)) {
            $this->postMessage($request->request->get('user'), $request->request->get('text'));
            $message = "Your entry has been added.";
        }

        $page = $request->query->get('p', 1);
        $tag = $request->query->get('tag');

        return $this->render(
            'TngWorkshopBoardBundle:Default:index.html.php',
            array(
                'message' => $message,
                'tags' => $boardService->getAllTags(),
                'num_pages' => ceil($boardService->countAllMessages() / self::DEFAULT_ENTRIES_PER_PAGE),
                'page' => $page,
                'entries' => $tag !== null
                    ? $boardService->getMessagesWithTag($tag)
                    : $boardService->getMessages($page, self::DEFAULT_ENTRIES_PER_PAGE)
            )
        );
    }

    /**
     * @param Request $request
     * @return bool
     */
    private function hasPostDataForNewMessage(Request $request)
    {
        return $request->request->get('user') !== null && $request->request->get('text') !== null;
    }

    /**
     * @param string $user
     * @param string $text
     */
    private function postMessage($user, $text)
    {
        /** @var BoardService $boardService */
        $boardService = $this->get(self::BOARD_SERVICE);

        $boardMessage = $boardService->postMessage($user, $text);
        foreach (HashTagFinder::findHashTagsIn($text) as $tag) {
            $boardService->linkMessageToTag($boardMessage, $tag);
        }
        $boardService->saveToDatabase();
    }
}
