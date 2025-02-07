@php
	$currencyexchangeEnabled = (config('settings.currencyexchange.activation') == '1');
	$currencies ??= [];
@endphp
@if ($currencyexchangeEnabled)
	@if (!empty($currencies))
		<li class="nav-item dropdown no-arrow open-on-hover">
			<a href="#" class="dropdown-toggle nav-link" data-bs-toggle="dropdown" data-target="#currenciesDropdownMenu">
				<span>{!! config('selectedCurrency.symbol') !!} {{ config('selectedCurrency.code') }}</span>
				<i class="fa-solid fa-chevron-down hidden-sm"></i>
			</a>
			<ul id="currenciesDropdownMenu" class="dropdown-menu user-menu">
				@foreach($currencies as $iCurr)
					@php
						$activeClass = ($iCurr->get('code') == config('selectedCurrency.code')) ? ' active' : '';
						
						$convertUrl = urlQuery()
							->removeAllParameters()
							->setParameters(['curr' => $iCurr->get('code')])
							->toString();
						
						$symbol = $iCurr->has('symbol') ? $iCurr->get('symbol') : '-';
					@endphp
					<li class="dropdown-item{{ $activeClass }}">
						<a href="{!! $convertUrl !!}">{!! $symbol !!} {{ $iCurr->get('code') }}</a>
					</li>
				@endforeach
			</ul>
		</li>
	@endif
@endif
