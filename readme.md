# Sample WP Events Plugin: WIS Events

## Overview

The Sample WP Events Plugin allows you to create, manage, and display events on your WordPress website. This plugin includes a custom post type for events, custom metadata fields for event details, and a shortcode to display events on the front end.

## Features

- Custom post type "Events"
- Custom fields for event metadata (e.g., start date, end date, start time, end time, location)
- Shortcode [display_wis_events] to display events on any page
- Easy-to-use admin interface for managing events
- Display event details on the event page

## Installation

- Download the plugin files.
- Upload the plugin folder to the /wp-content/plugins/ directory.
- Go to the WordPress admin dashboard, navigate to Plugins, and activate the "Sample WP Events Plugin."
- The "Event" custom post type will be available under the "Events" menu in the admin dashboard.
Usage

## Adding an Event

- Go to Events > Add New in the WordPress admin.
- Add a title for the event.
- Fill in the custom fields for the event's start and end dates, start and end times, and location.
- Publish the event.

## Displaying Events on the Front End

You can display events anywhere on your site using the [display_wis_events] shortcode. By default, the shortcode will show 5 events, but you can adjust the number of events by adding a number attribute:

[wis_events number="10"]
This will display 10 events.

## Event Page

To view an event's details, simply go to the event post, and it will automatically display the event metadata, including start date, end date, time, location, and description.

## Custom Fields

The following custom fields are available for each event:

- Start Date (_wis_event_start_date)
- End Date (_wis_event_end_date)
- Start Time (_wis_event_start_time)
- End Time (_wis_event_end_time)
- Location (_wis_event_location)

## Developer Notes

The plugin creates a custom post type "Event (`wis_event`)" using register_post_type().
It uses custom meta fields to store event-specific data such as dates and times.
The plugin provides a shortcode to display events on any page.
Metadata is displayed on the event page via template functions.

## Uninstalling

To uninstall the plugin, deactivate and delete it from the WordPress dashboard. All custom post types and metadata associated with the plugin will be removed.