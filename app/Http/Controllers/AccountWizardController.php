<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\User;
use App\Steps\Account\SetAddress;
use App\Steps\Account\SetCompanyBasicData;
use App\Steps\Account\SetCompanySecondaryData;
use App\Steps\Account\SetUserEmailAndPassword;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Smajti1\Laravel\Exceptions\StepNotFoundException;
use Smajti1\Laravel\Step;
use Smajti1\Laravel\Wizard;
use Illuminate\Support\Facades\Auth;

class AccountWizardController extends Controller
{
	/**
	 * @var array<int|string, class-string<Step>>
	 */
    public array $steps = [
        SetUserEmailAndPassword::class,
        SetCompanyBasicData::class,
        SetAddress::class,
        SetCompanySecondaryData::class,
    ];

    protected Wizard $wizard;

    public function __construct()
    {
        $this->wizard = new Wizard($this->steps, $sessionKeyName = 'user');

        view()->share(['wizard' => $this->wizard]);
    }

    public function wizard(Step|string|null $step = null): View
	{
        try {
            if (!$step) {
                $step = $this->wizard->firstOrLastProcessed();
            } else {
                $step = $step instanceof Step ? $step : $this->wizard->getBySlug($step);
            }
        } catch (StepNotFoundException $e) {
            abort(404);
        }
        /** @var User $user */
        $user = Auth::user();

        return view('wizard.account.base', compact('step', 'user'));
    }

    public function wizardPost(Request $request, Step|string|null $step = null): RedirectResponse
	{
		try {
            $step_by_slug = $step instanceof Step ? $step : $this->wizard->getBySlug($step ?? '');
        } catch (StepNotFoundException $e) {
            abort(404);
        }

        $this->validate($request, $step_by_slug->rules($request));
		$step_by_slug->process($request);

        if ($step_by_slug->index === ($this->wizard->limit() - 1)) {

            return redirect()->route('panel');
        }

        return redirect()->route('wizard.account', [$this->wizard->nextSlug()]);
    }

}
