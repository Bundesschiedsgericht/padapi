<?php
namespace OCA\PadApi\Controller;

use OCP\IRequest;
use OCP\AppFramework\Http\JSONResponse;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\Controller;
use OCP\IUserSession;
use OCP\IGroupManager;

class ApiController extends Controller {
	private $userId;
  private $userSession;
  private $groupManager;

	public function __construct($AppName,
															IRequest $request,
															IUserSession $userSession,
                              IGroupManager $groupManager,
															$UserId){
		parent::__construct($AppName, $request);
    $this->userSession = $userSession;
    $this->groupManager = $groupManager;
		$this->userId = $UserId;
	}

    /**
     * @PublicPage
     * @NoCSRFRequired
     *
     * @return JSONResponse
     */
    public function getUserinfo() {

        if ($this->userSession->isLoggedIn()) {

            $groups = [];

            foreach($this->groupManager->getUserGroups($this->userSession->getUser()) as $group) {
                $groups[] = $group->getGID();
            }

            return new JSONResponse(
                [
                    'username' => $this->userSession->getUser()->getUID(),
                    'displayname' =>  $this->userSession->getUser()->getDisplayName(),
                    "groups" => $groups,
                ]
            );
        }
        else
        {
            return new JSONResponse(
                [
                ]
            );
        }
    }

    /**
     * @PublicPage
     * @NoCSRFRequired
     *
     * @return JSONResponse
     */
    public function getGroupinfo() {

        $groups = [];

        foreach($this->groupManager->search('') as $group) {
            $groups[] = $group->getGID();
        }

        return new JSONResponse(
            [
                "groups" => $groups,
            ]
        );
    }

}
