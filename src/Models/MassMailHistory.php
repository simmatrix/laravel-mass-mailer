<?php

namespace Simmatrix\MassMailer\Models;

use Illuminate\Database\Eloquent\Model;

class MassMailHistory extends Model
{	
	protected $table = 'mass_mail_history';
	protected $fillable = [
		'subject',
		'mailing_list', 
		'params',
		'archive_link',
		'success',
	];
	public $timestamps = true;
}
