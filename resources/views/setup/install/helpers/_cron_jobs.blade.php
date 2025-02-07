@php
	$articleUrl = 'https://support.bedigit.com/help-center/articles/19/configuring-the-cron-job';
	
	$scheduleCmd = getRightPathsForCmd('php artisan schedule:run', withHint: false);
        $queueCmd = getRightPathsForCmd('php artisan queue:work --queue=mail,sms,thumbs,default --tries=3 --timeout=180 --max-time=300 --max-jobs=10 --sleep=10 --memory=256', withHint: false);
	$hintForCmd = getHintForPhpCmd();
@endphp
<h3 class="title-3">
    <i class="fa-regular fa-clock"></i> {{ trans('messages.setting_up_cron_jobs') }}
</h3>

<div class="alert {{ isAdminPanel() ? 'bg-light-info' : 'alert-info' }}">
    {!! trans('messages.cron_jobs_guide', ['articleUrl' => $articleUrl]) !!}
</div>

{!! $scheduleCmd !!}
{!! $queueCmd !!}
{!! $hintForCmd !!}
