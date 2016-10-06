<?php

namespace Simmatrix\MassMailer\Presenters;

use Simmatrix\MassMailer\Presenters\MassMailerPresenterAbstract;
use Simmatrix\MassMailer\Interfaces\MassMailerPresenterInterface;
use Simmatrix\MassMailer\ValueObjects\MassMailerParams;
use Simmatrix\MassMailer\MassMailerAttribute;

class DefaultMassMailerPresenter extends MassMailerPresenterAbstract implements MassMailerPresenterInterface
{
	/**
	 * @param Object An instance of Simmatrix\MassMailer\ValueObjects\MassMailerParams
	 */
	public function __construct( MassMailerParams $params )
	{
		parent::__construct( $params );
		self::setParameters();
	}

	/**
	 * To return the name of the blade view template file
	 * 
	 * @return String The file name
	 */
	public function getTemplate()
	{
		return 'vendor.simmatrix.mass-mailer.default';
	}

	/**
	 * To set any custom parameters that need to be added on top of the default list of parameters
	 *
	 * @return void
	 */
    private function setParameters()
    {
        parent::setViewParameters([
            'testing' => '--displaying a custom parameter in presenter object--',
        ]);
    }	
}