var React = require('react');
var Timeline = require('./timeline.jsx');

var RootComponent = React.createClass({
  getInitialState: function() {
    return {
      timeline_json: timeline_json
    };
  },
  render: function() {
    if (timeline_json.error) {
      return (
        <article id="timeline" className="row">
          <div className='timeline-item col-md-offset-3 col-md-6 error-msg'>
              <p>tweet not found.</p>
          </div>
        </article>
      );
    }

    return (
      <Timeline timeline_json={this.state.timeline_json} />
    );
  }
});

React.render(
  <RootComponent />,
  document.getElementById('container')
);
