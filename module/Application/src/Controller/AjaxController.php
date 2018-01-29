<?php
namespace Application\Controller;

use \Zend\Mvc\Controller\AbstractActionController;
use \Zend\Http\PhpEnvironment\Response as HttpResponse;

class AjaxController extends AbstractActionController
{
    use Traits\ControllerConstructor;

    /**
     * prepare output for output as JSON
     *
     * @param mixed $content
     * @return HttpResponse
     */
    private function prepareOutputAsJson($content)
    {
        /** @var $response HttpResponse */
        $response = $this->getResponse();
        $response->getHeaders()->addHeaderLine('Content-Type', 'application/json');

        return $response->setContent(json_encode($content));
    }

    /**
     * toggle unclear flag of translation
     * HTTP-Param: translation_id
     *
     * @return HttpResponse
     */
    public function toggleUnclearAction()
    {
        $json = [
            'error'     => null,
            'success'   => false,
            'new_state' => null,
        ];
        $translationId = (int)$this->params()->fromPost('translation_id');
        $translation = $this->_translationTable->getTranslation($translationId);
        if (false === $translation) {
            // translation does not exist

            $json['error'] = 'Unknown translation';
            return $this->prepareOutputAsJson($json);
        }

        $translation->setUnclearTranslation(!$translation->getUnclearTranslation());
        $success = $this->_translationTable->saveTranslation($translation);

        if (false === $success) {
            // error saving translation

            $json['error'] = 'Can not save translation';
            return $this->prepareOutputAsJson($json);
        }

        // toggle successfully
        $json['success'] = true;
        $json['new_state'] = $translation->getUnclearTranslation();

        return $this->prepareOutputAsJson($json);
    }
}
