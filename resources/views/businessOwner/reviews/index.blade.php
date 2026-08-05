@extends('layouts.businessOwner')

@section('title', 'Customer Reviews')

@section('content')
<div class="space-y-6">

    <!-- Header & Action Bar -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h3 class="text-xl font-bold text-slate-900">All Reviews</h3>
            <p class="text-sm text-slate-500">Manage, read, and respond to feedback from your clients.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="px-3 py-1 bg-orange-100 text-orange-700 text-xs font-semibold rounded-full">
                {{ $reviews->count() }} Total
            </span>
        </div>
    </div>

    <!-- Reviews Table Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-200 text-slate-500 text-xs font-semibold uppercase tracking-wider">
                        <th class="py-4 px-6">User</th>
                        <th class="py-4 px-6">Rating</th>
                        <th class="py-4 px-6">Comment</th>
                        <th class="py-4 px-6 min-w-[300px]">Response Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm">
                    @forelse ($reviews as $review)
                        <tr class="hover:bg-slate-50/50 transition-colors">

                            <!-- User Column -->
                            <td class="py-4 px-6 align-top">
                                <div class="flex items-center space-x-3">
                                    <div class="w-9 h-9 rounded-full bg-slate-100 border border-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs uppercase">
                                        {{ substr($review->user_name, 0, 2) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-slate-900 leading-snug">{{ $review->user_name }}</p>
                                        <span class="text-xs text-slate-400">Verified Customer</span>
                                    </div>
                                </div>
                            </td>

                            <!-- Rating Column -->
                            <td class="py-4 px-6 align-top">
                                <div class="flex items-center space-x-1">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <i data-lucide="star"
                                           class="w-4 h-4 {{ $i <= $review->rating ? 'text-amber-400 fill-amber-400' : 'text-slate-200' }}">
                                        </i>
                                    @endfor
                                    <span class="ml-1 text-xs font-semibold text-slate-600">{{ $review->rating }}.0</span>
                                </div>
                            </td>

                            <!-- Comment Column -->
                            <td class="py-4 px-6 align-top">
                                <p class="text-slate-700 leading-relaxed max-w-md">
                                    {{ $review->comment }}
                                </p>
                            </td>

                            <!-- Reply / Status Column -->
                            <td class="py-4 px-6 align-top">
                                @if($review->reply)
                                    <div class="bg-slate-50 border border-slate-200/80 rounded-xl p-3 space-y-1">
                                        <div class="flex items-center space-x-1.5 text-xs font-semibold text-orange-600">
                                            <i data-lucide="corner-down-right" class="w-3.5 h-3.5"></i>
                                            <span>Your Reply</span>
                                        </div>
                                        <p class="text-xs text-slate-700 font-medium">
                                            {{ $review->reply->reply }}
                                        </p>
                                    </div>
                                @else
                                    <form action="{{ route('business.review.reply', $review->id) }}" method="POST" class="space-y-2">
                                        @csrf
                                        <div class="relative">
                                            <textarea
                                                name="reply"
                                                rows="2"
                                                class="w-full rounded-xl border border-slate-200 p-3 text-xs text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 transition-all resize-none"
                                                placeholder="Write an official response..."
                                                required></textarea>
                                        </div>
                                        <div class="flex justify-end">
                                            <button type="submit"
                                                    class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-orange-600 hover:bg-orange-700 text-white text-xs font-semibold rounded-lg shadow-sm transition-colors focus:ring-2 focus:ring-orange-500/20">
                                                <i data-lucide="send" class="w-3 h-3"></i>
                                                <span>Send Reply</span>
                                            </button>
                                        </div>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="py-12 text-center text-slate-400">
                                <i data-lucide="message-square" class="w-8 h-8 mx-auto mb-2 opacity-50"></i>
                                <p class="text-sm">No reviews found yet.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
