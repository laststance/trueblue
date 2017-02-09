import autobind from 'autobind-decorator'
import React from 'react'

@autobind
export default class Timeline extends React.Component {

    render() {
        var view = ''
        if (typeof this.props.timelineJson == 'undefined' || !this.props.timelineJson.length || this.props.timelineJson.error) {
            view = (
                <div>
                    <div className="row" style={{margin: 0}}>
                        <section
                            className='timeline-item error-msg col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10'>
                            <p>tweet not found.</p>
                        </section>
                    </div>
                </div>
            )
        } else {
            view = this.props.timelineJson.map((tweet, index)=> {
                const date = new Date(tweet.created_at)
                return (
                    <div className="col-lg-offset-1 col-lg-10" key={tweet.id_str}>
                        <div className="row">
                            <section
                                className='timeline-item col-lg-offset-2 col-lg-8 col-md-offset-1 col-md-10 col-sm-offset-1 col-sm-10 col-xs-offset-1 col-xs-10'
                                data-id={tweet.id_str} data-index={index}>
                                <div className="contents">
                                    <div className="left-side">
                                        <img className="profile-image" src={tweet.user.profile_image_url}/>
                                    </div>
                                    <div className="right-side">
                                        <span className="user-name">{tweet.user.name}</span>
                                        <span className="screen-name">@{tweet.user.screen_name}</span>
                                        <p className="text" dangerouslySetInnerHTML={{__html: tweet.text}}></p>
                                        {( () => {
                                            let urls = tweet.entities.urls
                                            if(urls.length > 0) {
                                                const url = 'https://hatenablog-parts.com/embed?url=' + urls[urls.length-1].url
                                                return <p style={{margin:'10px 0'}}><iframe src={url} className="urlcard" scrolling="no" frameBorder="0" style={{width:'100%', height:'155px', maxWidth:'500px'}}></iframe></p>
                                            }
                                        })()}
                                        <p className="create-at">{date.getFullYear()}年{date.getMonth() + 1}月{date.getDate()}日 {date.getHours()}時{date.getMinutes()}分</p>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                )
            })
        }

        return (
            <article id="timeline" className="row">
                {view}
            </article>
        )
    }

}
