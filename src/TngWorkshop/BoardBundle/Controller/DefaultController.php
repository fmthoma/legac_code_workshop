<?php

namespace TngWorkshop\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TngWorkshop\BoardBundle\Service\BoardService;

class DefaultController extends Controller
{
    const DEFAULT_ENTRIES_PER_PAGE = 10;

    public function indexAction(Request $request)
    {
        /** @var BoardService $boardService */
        $boardService = $this->get('board.board_service');

        $message = "";
        if ($this->hasPostDataForNewMessage($request)) {
            $message = $boardService->postMessage($request->request->get('user'), $request->request->get('text'));
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
}
