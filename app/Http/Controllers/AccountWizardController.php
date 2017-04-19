<?php

namespace App\Http\Controllers;

use App\Steps\Account\SetAddress;
use App\Steps\Account\SetCompanyBasicData;
use App\Steps\Account\SetCompanySecondaryData;
use App\Steps\Account\SetUserEmailAndPassword;
use Illuminate\Http\Request;
use Smajti1\Laravel\Exceptions\StepNotFoundException;
use Smajti1\Laravel\Wizard;

class AccountWizardController extends Controller
{
    public $steps = [
        SetUserEmailAndPassword::class,
        SetCompanyBasicData::class,
        SetAddress::class,
        SetCompanySecondaryData::class,
    ];

    protected $wizard;

    public function __construct()
    {
        $this->wizard = new Wizard($this->steps, $sessionKeyName = 'user');

        view()->share(['wizard' => $this->wizard]);
    }

    public function wizard($step = null)
    {
        try {
            if (!$step) {
                $step = $this->wizard->firstOrLastProcessed();
            } else {
                $step = $this->wizard->getBySlug($step);
            }
        } catch (StepNotFoundException $e) {
            abort(404);
        }

        $user = \Auth::user();
        if ($user) {
            $companies = $user->companies();
        }

        return view('wizard.account.base', compact('step', 'user', 'companies'));
    }

    public function wizardPost(Request $request, $step = null)
    {
        try {
            $step = $this->wizard->getBySlug($step);
        } catch (StepNotFoundException $e) {
            abort(404);
        }

        $this->validate($request, $step->rules($request));
        $step->process($request);

        if ($step->index == ($this->wizard->limit() - 1)) {

            return redirect()->route('panel');
        }

        return redirect()->route('wizard.account', [$this->wizard->nextSlug()]);
    }

}