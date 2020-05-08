<?php
// backend PHP file
// this will complete the client's requests
/*
service ERRNO values:
0: everything is ok
1: unknown action
2: invalid input
3: page not found
*/

require_once 'libraries/formatted_text.php';

$data = json_decode(file_get_contents('php://input'), true);

// here handler functions will be defined
class Handler {
    function unknownAction(array $data) {
        // there is no such action
        $actionName = $data['action'];
        $response = array(
            'errno' => 1,
            'description' => "Nincs olyan akció, hogy '$actionName'"
        );
        echo json_encode($response);
    }

    function getPage(array $data) {
        // the client requested a page
        if(!isset($data['params'])) {
            $this->invalidInput();
            return;
        }
        $params = $data['params'];
        if(!isset($params['page'])) {
            $this->invalidInput();
            return;
        }
        $pageName = $params['page'];
        $pages = json_decode(file_get_contents('pages.json'), true);
        if(!isset($pages[$pageName])) {
            $response = array(
                'errno' => 3,
                'description' => "A lap '$pageName' nem létezik."
            );
            echo json_encode($response);
            return;
        }
        // give the client the page
        $pageData = $pages[$pageName];
        $pageTitle = $pageData['title'];
        if($pageData['type'] == 'text') {
            $pageContent = file_get_contents('pages/' . $pageData['file']);
        } elseif($pageData['type'] == 'formatted') {
            $pageContent = render(file_get_contents('pages/' . $pageData['file']));
        } elseif ($pageData['type'] == 'interactive') {
            ob_start();
            include('pages/interactive/' . $pageData['file']);
            $pageContent = ob_get_clean();
        }
        $response = array(
            'errno' => 0,
            'pageTitle' => $pageTitle,
            'pageContent' => $pageContent
        );
        echo json_encode($response);
    }

    private function invalidInput() {
        $response = array(
            'errno' => 2,
            'description' => 'Érvénytelen bemenet'
        );
        echo json_encode($response);
    }
}

// parse $data and see if we can handle it
$handler = new Handler();
header('Content-Type: application/json', true);
switch($data['action']) {
    case 'getPage':
        $handler->getPage($data);
        break;
    default:
        $handler->unknownAction($data);
        break;
}