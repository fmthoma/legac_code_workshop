<?php

namespace TngWorkshop\BoardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TngWorkshop\BoardBundle\Service\BoardService;

class DefaultController extends Controller
{
    const DEFAULT_ENTRIES_PER_PAGE = 10;

    public function indexAction()
    {
        /** @var BoardService $boardService */
        $boardService = $this->get('board.board_service');

        $message = "";
        if (isset($_POST['user']) && isset($_POST['text'])) {
            $message = $boardService->postMessage($_POST['user'], $_POST['text']);
        }

        $page = isset($_GET['p']) ? $_GET['p'] : 1;
        $tag = isset($_GET['tag']) ? $_GET['tag'] : null;


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
}
