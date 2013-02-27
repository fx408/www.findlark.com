package jrgo

import (
	"fmt"
	"mime"
	"net/http"
	"strings"
	"time"
)

type Request struct {
	ResponseWriter http.ResponseWriter
	Request        *http.Request
	Params         map[string]string
}

func (this *Request) WriteString(content string) {
	this.ResponseWriter.Write([]byte(content))
}

func (this *Request) Abort(status int, body string) {
	this.ResponseWriter.WriteHeader(status)
	this.ResponseWriter.Write([]byte(body))
}

func (this *Request) Redirect(status int, url_ string) {
	this.ResponseWriter.Header().Set("Location", url_)
	this.ResponseWriter.WriteHeader(status)
}

func (this *Request) NotModified() {
	this.ResponseWriter.WriteHeader(304)
}

func (this *Request) NotFound(message string) {
	this.ResponseWriter.WriteHeader(404)
	this.ResponseWriter.Write([]byte(message))
}

//Sets the content type by extension, as defined in the mime package. 
//For example, this.ContentType("json") sets the content-type to "application/json"
func (this *Request) ContentType(ext string) {
	if !strings.HasPrefix(ext, ".") {
		ext = "." + ext
	}
	ctype := mime.TypeByExtension(ext)
	if ctype != "" {
		this.ResponseWriter.Header().Set("Content-Type", ctype)
	}
}

func (this *Request) SetHeader(hdr string, val string, unique bool) {
	if unique {
		this.ResponseWriter.Header().Set(hdr, val)
	} else {
		this.ResponseWriter.Header().Add(hdr, val)
	}
}

//Sets a cookie -- duration is the amount of time in seconds. 0 = forever
func (this *Request) SetCookie(name string, value string, age int64) {
	var utctime time.Time
	if age == 0 {
		// 2^31 - 1 seconds (roughly 2038)
		utctime = time.Unix(2147483647, 0)
	} else {
		utctime = time.Unix(time.Now().Unix()+age, 0)
	}
	cookie := fmt.Sprintf("%s=%s; expires=%s", name, value, this.WebTime(utctime))
	this.SetHeader("Set-Cookie", cookie, false)
}

func (this *Request) WebTime(t time.Time) string {
	ftime := t.Format(time.RFC1123)
	if strings.HasSuffix(ftime, "UTC") {
		ftime = ftime[0:len(ftime)-3] + "GMT"
	}
	return ftime
}
