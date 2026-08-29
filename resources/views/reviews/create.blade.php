<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>{{ __('app.review_title') }} · ƉƆKUN</title>
<link href="https://fonts.bunny.net/css?family=dm-serif-display:400|manrope:400,600,700&display=swap" rel="stylesheet"/>
<script src="https://cdn.tailwindcss.com"></script>
<script>tailwind.config={theme:{extend:{colors:{dokun:{green:'#064E3B',gold:'#C99424',ivory:'#F8F6F0',charcoal:'#17201D'}},fontFamily:{sans:['Manrope'],serif:['"DM Serif Display"']}}}}</script>
<style>body{font-family:Manrope,sans-serif}.serif{font-family:'DM Serif Display',serif}</style>
</head>
<body class="bg-dokun-ivory text-dokun-charcoal min-h-screen">
@include('partials.navbar',['active'=>''])

<main class="pt-32 pb-20 max-w-xl mx-auto px-4">
 <div class="text-center mb-8">
 <div class="text-5xl mb-3">⭐</div>
 <h1 class="serif text-4xl text-dokun-green">{{ __('app.review_heading') }}</h1>
 <p class="text-gray-500 mt-2">{{ __('app.review_with', ['name' => $reservation->artisan->first_name]) }} · {{ $reservation->experience_type }}</p>
 </div>

 @if($errors->any())
 <div class="bg-red-50 border border-red-200 text-red-700 rounded-xl p-5 mb-6 text-sm">
 <ul class="list-disc list-inside">@foreach($errors->all() as $e)<li>{{$e}}</li>@endforeach</ul>
 </div>
 @endif

 <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8">
 <form action="{{ route('reviews.store') }}" method="POST" class="space-y-6">
 @csrf
 <input type="hidden" name="reservation_request_id" value="{{ $reservation->id }}">

 <!-- Note -->
 <div>
 <label class="block text-sm font-bold mb-3">{{ __('app.review_rating') }} *</label>
 <div class="flex gap-3" id="star-group">
 @for($i=1; $i<=5; $i++)
 <label class="cursor-pointer">
 <input type="radio" name="rating" value="{{$i}}" class="sr-only" @checked(old('rating')==$i)>
 <svg class="w-10 h-10 star-icon transition-colors" data-val="{{$i}}" fill="none" stroke="#C99424" stroke-width="1.5" viewBox="0 0 24 24">
 <path stroke-linecap="round" stroke-linejoin="round" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
 </svg>
 </label>
 @endfor
 </div>
 <p class="text-xs text-gray-400 mt-1" id="star-label">{{ __('app.review_click_rate') }}</p>
 </div>

 <!-- Commentaire -->
 <div>
 <label class="block text-sm font-bold mb-2">{{ __('app.review_comment') }} *</label>
 <textarea name="comment" rows="5" required minlength="10" maxlength="2000"
 class="w-full px-4 py-3 bg-dokun-ivory border border-gray-200 rounded-xl focus:ring-2 focus:ring-dokun-gold outline-none resize-none"
 placeholder="{{ __('app.review_comment_placeholder') }}">{{ old('comment') }}</textarea>
 <p class="text-xs text-gray-400 mt-1">{{ __('app.review_comment_hint') }}</p>
 </div>

 <button type="submit" class="w-full py-4 bg-dokun-green text-white font-bold text-lg rounded-xl hover:bg-dokun-green/90 transition">
 {{ __('app.review_submit') }}
 </button>
 </form>
 </div>
</main>

@include('partials.footer')
<script>
const LABELS = @json([
 '1' => '',
 '2' => __('app.review_l2'),
 '3' => __('app.review_l3'),
 '4' => __('app.review_l4'),
 '5' => __('app.review_l5'),
 '6' => __('app.review_l6'),
]);
document.querySelectorAll('input[name="rating"]').forEach(r => {
 r.addEventListener('change', () => {
 const val = parseInt(r.value);
 document.getElementById('star-label').textContent = LABELS[val] || '';
 document.querySelectorAll('.star-icon').forEach((s,i) => {
 s.setAttribute('fill', i < val ? '#C99424' : 'none');
 });
 });
});
// Restore old value
const old = {{ old('rating', 0) }};
if (old) document.querySelector(`input[name="rating"][value="${old}"]`)?.dispatchEvent(new Event('change'));
</script>
</body></html>
