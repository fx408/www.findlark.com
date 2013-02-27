package jrgo

import (
	"fmt"
	"log"
	"net/http"
	//"reflect"
	"regexp"
)

type UrlRegular struct {
	regular string
	to      string
}
type Server struct {
	JrBase
	UrlRegulars []*UrlRegular
}

func init() {
	regMap := make(map[string]string)
	regMap[`^/blog/(?P<id>\d+)`] = `/blog/show/index/$id`
	//regMap[`^/$`] = `/site/index`
	regMap[`^/(?P<name>\w+)$`] = `/$name/index`

	for k, v := range regMap {
		JrServer.UrlRegulars = append(JrServer.UrlRegulars, &UrlRegular{k, v})
	}
}

func (this *Server) ServeHTTP(w http.ResponseWriter, r *http.Request) {
	this.Run(w, r)
	return
}

func (this *Server) Run(w http.ResponseWriter, r *http.Request) {
	if r.URL.Path == "/favicon.ico" {
		return
	}

	//r.ParseMultipartForm(1024 * 1024 * 2) // 2M
	r.ParseForm()
	params := make(map[string]string)
	ctx := &Request{ResponseWriter: w, Request: r, Params: params}

	path := this.ReplacePath(r.URL.Path)
	call := JrRouting.Call(path, ctx)

	if call != true {
		http.NotFound(w, r)
	}

	fmt.Println(" path: ", r.URL.Path)
	fmt.Println(" Scheme: ", r.URL.Scheme)
	fmt.Println(" Fragment: ", r.URL.Fragment)
	fmt.Println(" Opaque: ", r.URL.Opaque)
	fmt.Println(" RawQuery: ", r.URL.RawQuery)

}

/**
 * 监听端口， 开启HTTP服务
 */
func (this *Server) StartServer() {

	err := http.ListenAndServe(":8082", this)
	if err != nil {
		log.Fatal("listenAndServer: ", err)
	}
}

/** 
 * 根据URL 规则，替换路径
 */
func (this *Server) ReplacePath(path string) string {
	var r *regexp.Regexp

	for _, reg := range this.UrlRegulars {
		r, _ = regexp.Compile(reg.regular)

		if r.MatchString(path) {
			path = r.ReplaceAllString(path, reg.to)
			break
		}
	}

	return path
}
