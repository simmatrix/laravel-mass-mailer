<?php

namespace Simmatrix\MassMailer\Models;

use Illuminate\Database\Eloquent\Model;

class MassMailDraft extends Model
{
	protected $table = 'mass_mail_draft';
    protected $fillable = [ 'name', 'params' ];
    public $timestamp = true;
}
