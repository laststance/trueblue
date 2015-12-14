var React = require('react');
var $ = require('jquery');
var Timeline = require('./timeline.jsx');
var Header = require('./header.jsx');


var RootComponent = React.createClass({
  getInitialState() {
    return {
      timeline_json: timeline_json,
      json_daily_url: json_daily_url,
      timeline_date_list: timeline_date_list,
      app_user_username: app_user_username
    };
  },
  getDailyJson(date) {
    var newDate = new Date();
    var today = newDate.getFullYear() + '-0' + (newDate.getMonth()+1) + '-' + newDate.getDate();

    if (date === today) {
      this.setState({timeline_json: timeline_json});
    } else {
      $.get(json_daily_url + '/' + date, function(json) {
        this.setState({timeline_json: json});
      }.bind(this));
    }
    return 0;
  },
  render() {
    return (
      <div>
        <Header getDailyJson={this.getDailyJson} timeline_date_list={this.state.timeline_date_list} app_user_username={this.state.app_user_username} />
        <Timeline timeline_json={this.state.timeline_json} />
      </div>
    );
  }
});

React.render(
  <RootComponent />,
  document.getElementById('container')
);
