const React = require('react');

const Timeline = React.createClass({
  render: function() {
    if (!this.props.timeline_json.length || this.props.timeline_json.error) {
      const view = (
        <article id="timeline" className="row">
          <div className='timeline-item col-md-offset-3 col-md-6 error-msg'>
              <p>tweet not found.</p>
          </div>
        </article>
      );
    } else {
      var view = this.props.timeline_json.map(function(tweet, index) {
        const date = new Date(tweet.created_at);
        return (
          <section className='timeline-item col-md-offset-3 col-md-6' key={tweet.id_str} data-id={tweet.id_str} data-index={index}>
            <div className="contents">
              <div className="pull-left">
                <img className="profile-image" src={tweet.user.profile_image_url} />
              </div>
              <div className="pull-right">
                <span className="user-name">{tweet.user.name}</span> <span className="screen-name">@{tweet.user.screen_name}</span>
              <p className="text">{tweet.text}</p>
              <p className="create-at">{date.getFullYear()}年{date.getMonth()+1}月{date.getDate()}日 {date.getHours()}時{date.getMinutes()}分</p>
              </div>
            </div>
          </section>
        );
      });
    }

    return (
      <article id="timeline" className="row">
        {view}
      </article>
    );
  }

});

module.exports = Timeline;
