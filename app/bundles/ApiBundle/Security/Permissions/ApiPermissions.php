<?php
/**
 * @package     Mautic
 * @copyright   2014 Mautic, NP. All rights reserved.
 * @author      Mautic
 * @link        http://mautic.com
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\ApiBundle\Security\Permissions;

use Symfony\Component\Form\FormBuilderInterface;
use Mautic\CoreBundle\Security\Permissions\CommonPermissions;

/**
 * Class ApiPermissions
 *
 * @package Mautic\UserBundle\Security\Permissions
 */
class ApiPermissions extends CommonPermissions
{

    public function __construct($params)
    {

        parent::__construct($params);

        $this->permissions = array(
            'access' => array(
                'full'     => 1024
            ),
            'clients' => array(
                'view'   => 1,
                'edit'   => 4,
                'create' => 8,
                'delete' => 32,
                'full'   => 1024
            )
        );
    }

    /**
     * {@inheritdoc}
     *
     * @return string|void
     */
    public function getName() {
        return 'api';
    }

    /**
     * {@inheritdoc}
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     * @param array                $data
     */
    public function buildForm(FormBuilderInterface &$builder, array $options, array $data)
    {
        $builder->add('api:access', 'button_group', array(
            'choices'  => array(
                'full'     => 'mautic.api.permissions.granted',
            ),
            'label'    => 'mautic.api.permissions.apiaccess',
            'expanded' => true,
            'multiple' => true,
            'attr'     => array(
                'onclick' => 'Mautic.onPermissionChange(this, event, \'api\')'
            ),
            'data'     => (!empty($data['access']) ? $data['access'] : array())
        ));

        $builder->add('api:clients', 'button_group', array(
            'choices'    => array(
                'view'   => 'mautic.core.permissions.view',
                'edit'   => 'mautic.core.permissions.edit',
                'create' => 'mautic.core.permissions.create',
                'delete' => 'mautic.core.permissions.delete',
                'full'   => 'mautic.core.permissions.full'
            ),
            'label'      => 'mautic.api.permissions.clients',
            'label_attr' => array('class' => 'control-label'),
            'expanded'   => true,
            'multiple'   => true,
            'attr'       => array(
                'onclick' => 'Mautic.onPermissionChange(this, event, \'api\')'
            ),
            'data'      => (!empty($data['clients']) ? $data['clients'] : array())
        ));
    }

    /**
     * {@inheritdoc}
     *
     * @param $name
     * @param $perm
     */
    public function getValue($name, $perm)
    {
        //ensure api is enabled system wide
        if (empty($this->params['api_enabled'])) {
            return 0;
        } else {
            return parent::getValue($name, $perm);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @return bool|mixed
     */
    public function isEnabled() {
        return !empty($this->params['api_enabled']);
    }

    /**
     * {@inheritdoc}
     *
     * @param $name
     * @param $level
     * @return array
     */
    protected function getSynonym($name, $level) {
        if ($name == "access" && $level == "granted") {
            $level = "full";
        } elseif ($name == "clients") {
            switch ($level) {
                case "viewown":
                case "viewother":
                    $level = "view";
                    break;
                case "editother":
                case "editown":
                    $level = "edit";
                    break;
                case "deleteother":
                case "deleteown":
                    $level = "delete";
                    break;
            }
        }

        return array($name, $level);
    }
}