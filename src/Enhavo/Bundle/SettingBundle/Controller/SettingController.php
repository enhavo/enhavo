<?php

namespace Enhavo\Bundle\SettingBundle\Controller;

use Enhavo\Bundle\AppBundle\Controller\ResourceController;
use Symfony\Component\HttpFoundation\Request;

class SettingController extends ResourceController
{
    public function editAction(Request $request)
    {
        $key = $request->get('key');

        $setting = $this->repository->findOneBy([
            'key' => $key
        ]);

        if (empty($setting)) {
            throw $this->createNotFoundException();
        }

        $query = [];
        foreach ($request->query as $key => $value) {
            $query[$key] = $value;
        }

        return $this->redirectToRoute('enhavo_setting_setting_update', array_merge([
            'id' => $setting->getId(),
        ], $query));
    }
}
