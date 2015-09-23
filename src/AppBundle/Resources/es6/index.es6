var React = require('react');
var Timeline = require('./timeline.jsx');

var RootComponent = React.createClass({
  getInitialState: function() {
    return {
      timeline_json: timeline_json
    };
  },
  render: function() {
    return (
      <Timeline timeline_json={this.state.timeline_json} />
    );
  }
});

React.render(
  <RootComponent />,
  document.getElementById('container')
);
