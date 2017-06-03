@extends('layouts.skeleton')

@section('content')
  <div class="dashboard">

    <!-- Page content -->
    <div class="main-content">

      <div class="{{ Auth::user()->getFluidLayout() }}">

        <div class="row">

          <div class="col-xs-12 col-sm-9">
          <!--
            % contacts with significant other
            % contacts with kids -->

            <ul class="nav nav-tabs" role="tablist">
              <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#coming" role="tab">What's coming</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#actions" role="tab">Latest actions</a>
              </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
              <div class="tab-pane active" id="coming" role="tabpanel">

                {{-- REMINDERS --}}
                <div class="reminders">
                  <h3>{{ trans('dashboard.reminders_title') }}</h3>

                  @if ($upcomingReminders->count() != 0)
                  <ul>
                    @foreach ($upcomingReminders as $reminder)
                      <li>
                        <span class="reminder-in-days">
                          in

                          {{ $reminder->next_expected_date->diffInDays(Carbon\Carbon::now()) }}

                          days

                          ({{ App\Helpers\DateHelper::getShortDate($reminder->getNextExpectedDate(), Auth::user()->locale) }})
                        </span>
                        <a href="/people/{{ $reminder->contact_id }}">{{ App\Contact::find($reminder->contact_id)->getCompleteName() }}</a>:
                        {{ $reminder->getTitle() }}
                      </li>
                    @endforeach
                  </ul>

                  @else

                  <p>{{ trans('dashboard.reminders_blank_description') }}</p>

                  @endif
                </div>

                {{-- TASKS --}}
                <div class="tasks">
                  <h3>Tasks</h3>

                  @if ($tasks->count() != 0)
                  <ul>
                    @foreach ($tasks as $task)
                      <li>
                        <a href="/people/{{ $reminder->contact_id }}">{{ App\Contact::find($task->contact_id)->getCompleteName() }}</a>:
                        {{ $task->getTitle() }}
                        {{ $task->getDescription() }}
                      </li>
                    @endforeach
                  </ul>

                  @else

                  <p>No tasks are planned.</p>

                  @endif
                </div>
              </div>
              <div class="tab-pane" id="actions" role="tabpanel">
                <h3>{{ trans('dashboard.event_title') }}</h3>
                <ul class="event-list">
                  @foreach($events as $event)
                    <li class="event-list-item">

                      {{-- ICON--}}
                      <div class="event-icon">
                        @if ($event['nature_of_operation'] == 'create')
                          <i class="fa fa-plus-square-o"></i>
                        @endif

                        @if ($event['nature_of_operation'] == 'update')
                          <i class="fa fa-pencil-square-o"></i>
                        @endif
                      </div>

                      {{-- DESCRIPTION --}}
                      <div class="event-description">

                        {{-- YOU ADDED/YOU UPDATED --}}
                        @if ($event['nature_of_operation'] == 'create')
                          {{ trans('dashboard.event_create') }}
                        @endif

                        @if ($event['nature_of_operation'] == 'update')
                          {{ trans('dashboard.event_update') }}
                        @endif

                        {{-- PEOPLE --}}
                        @if ($event['object_type'] == 'contact')
                          @include('dashboard.events._people')
                        @endif

                        {{-- REMINDERS --}}
                        @if ($event['object_type'] == 'reminder')
                          @include('dashboard.events._reminders')
                        @endif

                        {{-- SIGNIFICANT OTHER --}}
                        @if ($event['object_type'] == 'significantother')
                          @include('dashboard.events._significantothers')
                        @endif

                        {{-- KIDS --}}
                        @if ($event['object_type'] == 'kid')
                          @include('dashboard.events._kids')
                        @endif

                        {{-- NOTES --}}
                        @if ($event['object_type'] == 'note')
                          @include('dashboard.events._notes')
                        @endif

                        {{-- ACTIVITIES --}}
                        @if ($event['object_type'] == 'activity')
                          @include('dashboard.events._activities')
                        @endif

                        {{-- TASKS --}}
                        @if ($event['object_type'] == 'task')
                          @include('dashboard.events._tasks')
                        @endif

                        {{-- GIFTS --}}
                        @if ($event['object_type'] == 'gift')
                          @include('dashboard.events._gifts')
                        @endif

                        {{-- DEBTS --}}
                        @if ($event['object_type'] == 'debt')
                          @include('dashboard.events._debts')
                        @endif

                      </div>

                      {{-- DATE --}}
                      <div class="event-date">
                        {{ $event['date'] }}
                      </div>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>

          </div>

          {{-- Sidebar --}}
          <div class="col-xs-12 col-sm-3 sidebar">

            <!-- Add activity  -->
            <div class="sidebar-cta hidden-xs-down">
              <a href="/people/add" class="btn btn-primary">{{ trans('app.main_nav_cta') }}</a>
            </div>

            <div class="sidebar-box last-seen">
              <h3>Last edited contacts</h3>
              @foreach ($lastUpdatedContacts as $contact)

                @if (count($contact->getInitials()) == 1)
                <div class="avatar one-letter hint--bottom" aria-label="{{ $contact->getCompleteName() }}" style="background-color: {{ $contact->getAvatarColor() }};">
                  {{ $contact->getInitials() }}
                </div>
                @else
                <div class="avatar hint--bottom" aria-label="{{ $contact->getCompleteName() }}" style="background-color: {{ $contact->getAvatarColor() }};">
                  {{ $contact->getInitials() }}
                </div>
                @endif

              @endforeach

              <p><a href="/people">See all other contacts</a></p>
            </div>

          </div>
        </div>

        <div class="row">
          <div class="col-xs-12">
            <div class="dashboard-box">
              <h2>Statistics about your account</h2>
              <ul class="horizontal dashboard-stat">
                <li>
                  <span class="stat-number">{{ $number_of_contacts }}</span>
                  <span class="stat-description">Number of contacts</span>
                </li>
                <li>
                  <span class="stat-number">{{ $number_of_kids }}</span>
                  <span class="stat-description">Number of kids</span>
                </li>
                <li>
                  <span class="stat-number">{{ $number_of_reminders }}</span>
                  <span class="stat-description">Number of reminders</span>
                </li>
                <li>
                  <span class="stat-number">{{ $number_of_notes }}</span>
                  <span class="stat-description">Number of notes</span>
                </li>
                <li>
                  <span class="stat-number">{{ $number_of_activities }}</span>
                  <span class="stat-description">Number of activities</span>
                </li>
                <li>
                  <span class="stat-number">{{ $number_of_gifts }}</span>
                  <span class="stat-description">Number of gifts</span>
                </li>
                <li>
                  <span class="stat-number">{{ $number_of_tasks }}</span>
                  <span class="stat-description">Number of tasks</span>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
@endsection
