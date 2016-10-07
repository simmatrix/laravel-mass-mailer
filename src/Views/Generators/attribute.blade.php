<?= '<?php' ?>


namespace {{ $namespace }};

use Simmatrix\MassMailer\Attributes\MassMailerAttributeAbstract;
use Simmatrix\MassMailer\Interfaces\MassMailerAttributeInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerAttributeParams;

class {{ $class_name }} extends MassMailerAttributeAbstract implements MassMailerAttributeInterface
{
@if( count( $parameters ) )
@foreach( $parameters as $parameter )
    /**
     * @var ${{ $parameter }}
     */
    public ${{ $parameter }};

@endforeach
@endif
    public function __construct()
    {
        // Write some optional initialization over here...
    }

    /**
     * @return Array An array that can be used in the blade template
     */
    public function get()
    {
        return MassMailerAttributeParams::create([
            'className' => {{ $class_name }}::class,
            'label' => '{{ $class_name }}',
            'name' => '{{ $name }}',
            'params' => $this -> getParams( $this ),
        ]);
    }

    /**
     * @return Array [ yourClassProperty => someValue ]
     */
    public function getParams( MassMailerAttributeInterface $class )
    {
        // You may tweak this section to return your own custom array
        return parent::getParams( $this );
    } 
}