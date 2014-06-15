Mapeditor.Shepherd = {};

Mapeditor.Shepherd.tour = new Shepherd.Tour({
  defaults: {
    classes: 'shepherd-element shepherd-open shepherd-theme-arrows',
    scrollTo: false,
    showCancelLink: false
  }
});

Mapeditor.Shepherd.tour.addStep('shepherd-new-map', {
  text: 'Hover the map menu to see available options.',
  attachTo: {
    element: '#menu .mapname',
    on: 'bottom'
  },
  buttons: [
    {
      text: 'Hide tour',
      classes: 'shepherd-button-secondary',
      action: Mapeditor.Shepherd.tour.cancel
    },
    {
      text: 'Next',
      action: Mapeditor.Shepherd.tour.next
    }
  ]
});

Mapeditor.Shepherd.tour.addStep('shepherd-toggle-canvasmode', {
  text: ['The editor always loads the map in something called Viewing Mode. This means that you can drag the mouse to move around on the map.', 'To switch to Painting Mode, press <kbd>space</kbd> or click on a brush.'],
  buttons: [
    {
      text: 'Hide tour',
      classes: 'shepherd-button-secondary',
      action: Mapeditor.Shepherd.tour.cancel
    },
    {
      text: 'Previous',
      action: Mapeditor.Shepherd.tour.back
    },
    {
      text: 'Next',
      action: Mapeditor.Shepherd.tour.next
    }
  ]
});

Mapeditor.Shepherd.tour.addStep('shepherd-canedit', {
  text: 'If you loaded a map that someone shared with you, they might not have allowed you to edit it. You will know this because enabling Painting Mode will not work.',
  buttons: [
    {
      text: 'Hide tour',
      classes: 'shepherd-button-secondary',
      action: Mapeditor.Shepherd.tour.cancel
    },
    {
      text: 'Previous',
      action: Mapeditor.Shepherd.tour.back
    },
    {
      text: 'Next',
      action: Mapeditor.Shepherd.tour.next
    }
  ]
});

Mapeditor.Shepherd.tour.addStep('shepherd-sharing', {
  text: ['If you are the owner of a map you can share it with other users to take advantage of the multiplayer experience.'],
  
  attachTo: {
    element: '#menu a[href="#share"]',
    on: 'bottom'
  },
  buttons: [
    {
      text: 'Hide tour',
      classes: 'shepherd-button-secondary',
      action: Mapeditor.Shepherd.tour.cancel
    },
    {
      text: 'Previous',
      action: Mapeditor.Shepherd.tour.back
    },
    {
      text: 'Okay, got it!',
      action: Mapeditor.Shepherd.tour.next
    }
  ]
});
