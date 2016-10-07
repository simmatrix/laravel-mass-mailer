<?= '<?php' ?>


namespace {{ $namespace }};

use Simmatrix\MassMailer\Presenters\MassMailerPresenterAbstract;
use Simmatrix\MassMailer\Interfaces\MassMailerPresenterInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\MassMailerAttribute;

class {{ $class_name }} extends MassMailerPresenterAbstract implements MassMailerPresenterInterface
{
    /**
     * @param Object An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
     */    
    public function __construct( MassMailerParams $params )
    {
        parent::__construct( $params );
        self::setParameters( $params );
    }

    /**
     * To return the name of the blade view template file
     * 
     * @return String The file name
     */
    public function getTemplate()
    {
        return '{{ $template_name }}';
    }

    /**
     * To set any custom parameters that need to be added on top of the default list of parameters
     *
     * @return void
     */
    public function setParameters( MassMailerParams $params )
    {
        parent::setViewParameters([     
            // 'xxx' => 'yyy',
            // 'yourAttributeName' => MassMailerAttribute::extract( $params, $yourAttributeName, $yourTargetedAttributeParam ),
        ]);
    }
}