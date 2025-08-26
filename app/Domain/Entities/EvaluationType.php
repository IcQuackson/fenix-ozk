<?php
declare(strict_types=1);

namespace App\Domain\Entities;

enum EvaluationType: string
{
	case TEST = 'TEST';
	case EXAM = 'EXAM';
	case PROJECT = 'PROJECT';
	case ONLINE_TEST = 'ONLINE_TEST';
	case AD_HOC = 'AD_HOC';
}
