var React = require('react');
var $ = require('jquery');
var Timeline = require('./timeline.jsx');
var Menu = require('./menu.jsx');

var TimelineComponent = React.createClass({
  getInitialState() {
    return {
      timeline_json: timeline_json,
      pastday_json_url: pastday_json_url,
      past_timeline_date_list: past_timeline_date_list
    };
  },
  getPastDayJson(date) {
    $.get(pastday_json_url + '/' + date, function(json) {
      this.setState({timeline_json: json});
    }.bind(this));
  },
  render() {
    return (
      <div>
        <Timeline timeline_json={this.state.timeline_json} />
        <Menu onClick={this.getPastDayJson} past_timeline_date_list={this.state.past_timeline_date_list} />
      </div>
    );
  }
});

React.render(
  <TimelineComponent />,
  document.getElementById('container')
);
