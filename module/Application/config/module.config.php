<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Doctrine\ORM\Mapping\Driver\AnnotationDriver;

return [
  'doctrine' => array(
          'driver' => array(
              // defines an annotation driver with two paths, and names it `my_annotation_driver`
              'my_annotation_driver' => array(
                  'class' => AnnotationDriver::class,
                  'cache' => 'array',
                  'paths' => array(
                      __DIR__ . "/src/Model"
                  ),
              ),
              // default metadata driver, aggregates all other drivers into a single one.
              // Override `orm_default` only if you know what you're doing
              'orm_default' => array(
                  'drivers' => array(
                      // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                      'Application\Model' => 'my_annotation_driver'
                  )
              )
          )
      ),
    'router' => [
        'routes' => [
            'home' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'application' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application[/:action]',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
            'app_id' => [
                'type'    => Segment::class,
                'options' => [
                    'route'    => '/application/[:action]/[:id][/]',
                    'defaults' => [
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => Controller\IndexController::class,
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => Controller\Factory\IndexControllerFactory::class,
        ],
    ],
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => [
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
